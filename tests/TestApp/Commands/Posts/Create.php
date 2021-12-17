<?php

namespace TestApp\Commands\Posts;

class Create extends AbstractCommand
{
    public function __construct(
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
