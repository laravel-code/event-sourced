<?php

namespace LaravelCode\EventSouring\Models;

use Illuminate\Database\Eloquent\Model;
use LaravelCode\EventSouring\Models\Events\EventWasCreated;

/**
 * LaravelCode\EventSouring\Models\Event.
 *
 * @property string $id
 * @property string $entity_id
 * @property string|null $command_id
 * @property string|null $author_id
 * @property Event $payload
 * @property string $type
 * @property string $status
 * @property int $version
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Event newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Event newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Event query()
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereCommandId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereEntityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event wherePayload($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Event whereVersion($value)
 * @mixin \Eloquent
 */
class Event extends Model
{
    protected $casts = [
        'id' => 'string',
        'payload' => 'json',
    ];

    public static function init(string|int $id, string $type, \LaravelCode\EventSouring\Contracts\Event\Event $payload, string $status, string $author = null)
    {
        event(new EventWasCreated(
            $id,
            $type,
            $payload,
            $status,
            $author
        ));
    }

    public function applyEventWasCreated(EventWasCreated $event)
    {
        $this->id = $event->id;
        $this->type = $event->type;
        $this->payload = $event->payload;
        $this->status = $event->status;
        $this->author_id = $event->author;
    }
}
