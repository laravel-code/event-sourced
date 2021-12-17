<?php

namespace TestApp\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use LaravelCode\EventSouring\Events\EventRecorder;
use TestApp\Events\Posts\BodyWasChanged;
use TestApp\Events\Posts\StatusWasChanged;
use TestApp\Events\Posts\TitleWasChanged;
use TestApp\Events\Posts\WasCreated;
use TestApp\Models\Concerns\UsesUuid;

class Post extends Model
{
    use EventRecorder, UsesUuid;

    public static function make(
        string $title,
        string $body,
        string $status,
        string $secretKey
    ): static {
        $entity = new static();
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

    public function applyWasCreated(WasCreated $event)
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
    ): static {
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

    public function applyTitleWasChanged(TitleWasChanged $event)
    {
        $this->title = $event->title;
    }

    public function applyBodyWasChanged(BodyWasChanged $event)
    {
        $this->body = $event->body;
    }

    public function applyStatusWasChanged(StatusWasChanged $event)
    {
        $this->status = $event->status;
    }
}
