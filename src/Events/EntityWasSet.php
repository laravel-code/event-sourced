<?php

namespace LaravelCode\EventSourcing\Events;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\SerializesModels;

class EntityWasSet
{
    use SerializesModels;

    public function __construct(public Model $model)
    {
    }
}
