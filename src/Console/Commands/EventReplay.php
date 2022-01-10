<?php

namespace LaravelCode\EventSourcing\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use LaravelCode\EventSourcing\Models\Event;
use LaravelCode\EventSourcing\Payload;

class EventReplay extends Command
{
    private int $count = 0;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'event:replay
     {--entity-id= : Replay commands from Model with an ID}
     {--command-id= : Replay a commandId}
     {--status= : handled | failed | received}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Replay events';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        Event::orderBy('created_at')
            ->where(function (Builder $query) {
                if ($this->option('entity-id')) {
                    $query->where('entity_id', $this->option('entity-id'));
                }

                if ($this->option('command-id')) {
                    $query->where('command_id', $this->option('command-id'));
                }

                $query->whereHas('command', function (Builder $query) {
                    if ($this->option('status')) {
                        $query->where('status', $this->option('status'));
                    } else {
                        $query->where('status', 'handled');
                    }
                });
            })
            ->chunk(200, function ($events) {
                /** @var Event $event */
                foreach ($events as $event) {
                    $this->count++;
                    try {
                        if (class_exists($event->type)) {
                            /** @var \LaravelCode\EventSourcing\Contracts\Event\Event $replayEvent */
                            $replayEvent = call_user_func([$event->type, 'fromPayload'], new Payload($event->payload));
                            $replayEvent->setBeingReplayed(true);
                            $replayEvent->setCommandId($event->command_id);
                            $replayEvent->setAuthorId($event->author_id);
                            $replayEvent->setVersion($event->version);
                            $replayEvent->setCreatedAt($event->created_at);
                            $replayEvent->setUpdatedAt($event->updated_at);
                            event($replayEvent);
                        }
                    } catch (\Exception $exception) {
                        $this->error($exception->getMessage());
                        $this->line($exception->getTraceAsString());
                    }
                }
            });

        $this->line(sprintf('Replayed Events: %s', $this->count));
    }
}
