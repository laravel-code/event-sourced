<?php

namespace LaravelCode\EventSouring\Models;

use Illuminate\Database\Eloquent\Model;
use LaravelCode\EventSouring\Contracts\Command\Command as CommandInterface;
use LaravelCode\EventSouring\Contracts\Command\ShouldStore;
use LaravelCode\EventSouring\Events\EventRecorder;
use LaravelCode\EventSouring\Models\Events\CommandWasCreated;

/**
 * LaravelCode\EventSouring\Models\Command.
 *
 * @property string $id
 * @property string|null $author_id
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
class Command extends Model
{
    use EventRecorder;

    const STATUS_RECEIVED = 'received';
    const STATUS_HANDLED = 'handled';
    const STATUS_SUCCEEDED = 'succeeded';
    const STATUS_FAILED = 'failed';

    protected $casts = [
        'id' => 'string',
        'payload' => 'json',
    ];

    public static function instance(string $id, string $type, ShouldStore $payload, string $status, string $author = null): self
    {
        $entity = new static();
        $entity->record(new CommandWasCreated(
            $id,
            $type,
            $payload,
            $status,
            $author
        ));

        return $entity;
    }

    public function applyCommandWasCreated(CommandWasCreated $event)
    {
        $this->id = $event->id;
        $this->type = $event->type;
        $this->payload = $event->payload;
        $this->status = $event->status;
        $this->author_id = $event->author;
    }
}
