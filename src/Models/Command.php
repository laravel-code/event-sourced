<?php

namespace LaravelCode\EventSourcing\Models;

use Illuminate\Database\Eloquent\Model;
use LaravelCode\EventSourcing\Contracts\Command\Command as CommandInterface;
use LaravelCode\EventSourcing\Contracts\Command\ShouldStore;
use LaravelCode\EventSourcing\Events\EventRecorder;
use LaravelCode\EventSourcing\Models\Concerns\UseSearchBehavior;
use LaravelCode\EventSourcing\Models\Events\CommandWasCreated;

/**
 * LaravelCode\EventSourcing\Models\Command.
 *
 * @property string $id
 * @property string|null $author_id
 * @property string $model
 * @property string $status
 * @property CommandInterface $payload
 * @property string $type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Command newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Command newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Command query()
 * @method static \Illuminate\Database\Eloquent\Builder|Command whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Command whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Command whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Command wherePayload($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Command whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Command whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Command whereUpdatedAt($value)
 * @mixin \Eloquent
 */
final class Command extends Model
{
    use EventRecorder, UseSearchBehavior;

    const STATUS_RECEIVED = 'received';
    const STATUS_HANDLED = 'handled';
    const STATUS_SUCCEEDED = 'succeeded';
    const STATUS_FAILED = 'failed';

    protected $casts = [
        'id' => 'string',
        'payload' => 'json',
    ];

    protected array $includes = [
        'error',
        'event',
    ];

    protected array $orderFields = [
        'type',
        'created_at',
        'updated_at',
    ];

    public static function instance(string $id, string $model, string $type, ShouldStore $payload, string $status, string $author = null): self
    {
        $entity = new static();
        $entity->record(new CommandWasCreated(
            $id,
            $model,
            $type,
            $payload,
            $status,
            $author
        ));

        return $entity;
    }

    public function applyCommandWasCreated(CommandWasCreated $event): void
    {
        $this->id = $event->id;
        $this->model = $event->model;
        $this->type = $event->type;
        $this->payload = $event->payload;
        $this->status = $event->status;
        $this->author_id = $event->author;
    }

    public function error(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CommandError::class);
    }

    public function events(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Event::class);
    }
}
