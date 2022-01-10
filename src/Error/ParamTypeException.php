<?php

namespace LaravelCode\EventSourcing\Error;

class ParamTypeException extends \RuntimeException
{
    /**
     * @var string
     */
    protected $message = 'Unable to find _from / _to object handler';
}
