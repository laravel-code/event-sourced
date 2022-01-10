<?php

namespace LaravelCode\EventSourcing\Error;

use Throwable;

class PayloadException extends \LogicException
{
    /**
     * @var string
     */
    protected $message = 'Unable to set param %s in payload. The param is already initiated.';

    /**
     * @param array $params
     * @param int $code
     * @param null|Throwable $previous
     */
    public function __construct(array $params, int $code = 0, $previous = null)
    {
        $message = sprintf($this->message, ...$params);
        parent::__construct($message, $code, $previous);
    }
}
