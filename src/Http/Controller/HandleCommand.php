<?php

namespace LaravelCode\EventSourcing\Http\Controller;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use LaravelCode\EventSourcing\Contracts\Command\Command;
use LaravelCode\EventSourcing\Events\AfterCommandWasHandled;
use LaravelCode\EventSourcing\Events\EntityWasSet;
use LaravelCode\EventSourcing\Payload;
use Webmozart\Assert\Assert;

trait HandleCommand
{
    private ?Model $entity;

    private ?JsonResponse $response;

    protected array $_defaultConfig = [
        'createUUID' => null,
        'entity' => null,
        'idParamName' => null,
    ];

    /**
     * OPossible options.
     *
     * entity: return the whole entity ['*'] or
     * Define the fields to return ['id', 'name', 'relation.id', 'relation.value']
     *
     * $opts = [
     *   'entity' => ['id'],
     *   'idParamName' => 'dummy'
     * ]
     *
     *
     * @param string $className
     * @param array $opts
     * @return JsonResponse|null
     */
    private function handleCommand(string $className, array $opts = []): ?JsonResponse
    {
        $opts = array_merge($this->_defaultConfig, $opts);
        /**
         * Phpstan is difficult.
         */
        /** @var Request $request */
        $request = \Illuminate\Support\Facades\Request::instance();

        if (empty($opts['idParamName'])) {
            $idParamName = $this->extractRouteIdParamName();
        } else {
            $idParamName = $opts['idParamName'];
        }

        $command = call_user_func([$className, 'fromPayload'], Payload::fromRequest($request, $idParamName));

        $this->setupListeners($command, $opts);
        dispatch($command);

        if (isset($this->response)) {
            return $this->response;
        }

        return null;
    }

    /**
     * @param Command $command
     * @param array $opts
     * @return void
     */
    private function setupListeners(Command $command, array $opts)
    {
        /*
         * When running in the console, an api response is not expected.
         */
        if (app()->runningInConsole() && !app()->runningUnitTests()) {
            return;
        }

        Event::listen(EntityWasSet::class, function (EntityWasSet $event) {
            if (isset($event->model)) {
                $this->entity = $event->model;
            }
        });

        Event::listen(AfterCommandWasHandled::class, function () use ($opts, $command) {
            $data = null;
            if ($this->entity) {
                $data = $this->extractFromEntity($this->entity->toArray(), $opts['entity']);
            }

            $this->sendResponse($command->getCommandId(), $data);
        });
    }

    /**
     * @param string $commandId
     * @param array|null $data
     * @return void
     */
    private function sendResponse(string $commandId, array $data = null)
    {
        if ($data) {
            $this->response = response()->json([
                'success' => true,
                'command_id' => $commandId,
                'entity' => $data,
            ]);

            return;
        }

        $this->response = response()->json([
            'success' => true,
            'command_id' => $commandId,
        ]);
    }

    /**
     * @param array $entity
     * @param array|null $opts
     * @return array|null
     */
    private function extractFromEntity(array $entity, array|null $opts): array|null
    {
        if ($opts === null) {
            return null;
        }

        $data = [];
        foreach ($opts as $field) {
            Assert::string($field, 'Entity extract fields can only handle strings with or without dotted notation');

            if ($field === '*') {
                return $entity;
            }

            if (strpos($field, '.')) {
                $data[$field] = Arr::get($entity, $field);

                continue;
            }

            $data[$field] = $entity[$field];
        }

        return !empty($data) ? self::undot($data) : null;
    }

    /**
     * @param array $array
     * @return array
     */
    public static function undot(array $array): array
    {
        $results = [];

        foreach ($array as $key => $value) {
            Arr::set($results, $key, $value);
        }

        return $results;
    }

    /**
     * @return string
     */
    private function extractRouteIdParamName(): string
    {
        $reflection = new \ReflectionClass($this);
        $name = $reflection->getShortName();

        return Str::camel(Str::singular(substr($name, 0, -10)));
    }
}
