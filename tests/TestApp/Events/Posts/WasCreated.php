<?php

namespace TestApp\Events\Posts;

class WasCreated extends AbstractEvent
{
    public function __construct(
        public string $id,
        public string $title,
        public string $body,
        public string $status,
        protected string $secretKey
    ) {
    }

    public function getSecretKey(): string
    {
        return $this->secretKey;
    }
}
