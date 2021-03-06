<?php

namespace TestApp\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use LaravelCode\EventSourcing\Events\EventRecorder;
use LaravelCode\EventSourcing\Models\Concerns\HasSearchBehavior;
use TestApp\Events\Posts\BodyWasChanged;
use TestApp\Events\Posts\StatusWasChanged;
use TestApp\Events\Posts\TitleWasChanged;
use TestApp\Events\Posts\WasCreated;
use TestApp\Models\Concerns\UsesUuid;

/**
 * @property string $id
 * @property string $title
 * @property string $body
 * @property string $status
 * @property string $secret_key
 */
final class Post extends Model
{
    use EventRecorder, UsesUuid, HasSearchBehavior;

    /**
     * @throws \LaravelCode\EventSourcing\Error\MethodNotImplemented
     * @throws \ReflectionException
     */
    public static function create(
        string $title,
        string $body,
        string $status,
        string $secretKey
    ): self {
        $entity = new self();
        $entity->id = Str::uuid();
        $entity->record(new WasCreated(
            $entity->id,
            $title,
            $body,
            $status,
            $secretKey
        ));

        return $entity;
    }

    public function applyWasCreated(WasCreated $event): void
    {
        $this->title = $event->title;
        $this->body = $event->body;
        $this->status = $event->status;
        $this->secret_key = $event->getSecretKey();
    }

    public function change(
        string $title,
        string $body,
        string $status,
    ): void {
        if ($this->title !== $title) {
            $this->record(new TitleWasChanged($this->id, $title));
        }

        if ($this->body !== $body) {
            $this->record(new BodyWasChanged($this->id, $body));
        }

        if ($this->status !== $status) {
            $this->record(new StatusWasChanged($this->id, $status));
        }
    }

    public function applyTitleWasChanged(TitleWasChanged $event): void
    {
        $this->title = $event->title;
    }

    public function applyBodyWasChanged(BodyWasChanged $event): void
    {
        $this->body = $event->body;
    }

    public function applyStatusWasChanged(StatusWasChanged $event): void
    {
        $this->status = $event->status;
    }
}
