<?php
/**
 * @deprecated this file is not needed?
 */

namespace LaravelCode\EventSourcing\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use LaravelCode\EventSourcing\Models\Concerns\HasSearchBehavior;

/**
 * LaravelCode\EventSourcing\Models\Event.
 *
 * @property string $id
 * @property string $model
 * @property string $entity_id
 * @property string|null $command_id
 * @property string|null $author_id
 * @property \LaravelCode\EventSourcing\Contracts\Event\Event|array $payload
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
    use HasSearchBehavior;

    protected $casts = [
        'id' => 'string',
        'payload' => 'json',
    ];

    protected array $includes = [
        'command',
        'command.error',
    ];

    protected array $orderFields = [
        'version',
        'type',
        'created_at',
        'updated_at',
    ];

    protected function search(): array
    {
        return [
            'id',
            'resource_id',
            'type' => function (Builder $query, $value) {
                if (class_exists($value)) {
                    return $query->where('class', $value);
                }

                return $query->where('class', $value);
            },
            'command_id',
            'author_id',
        ];
    }

    public function command(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Command::class);
    }
}
