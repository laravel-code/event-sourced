<?php

namespace LaravelCode\EventSouring\Events;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\SerializesModels;

class EntityWasSet
{
    use SerializesModels;

    public function __construct(public Model $model)
    {
    }
}
