<?php

namespace TestApp\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use LaravelCode\EventSouring\Http\Controller\HandleCommand;
use TestApp\Commands\Posts\Change;
use TestApp\Commands\Posts\Create;
use TestApp\Commands\Posts\NoHandle;
use TestApp\Commands\Posts\StopPropagation;

class PostController extends Controller
{
    use HandleCommand;

    public function store(): ?JsonResponse
    {
        return $this->handleCommand(Create::class, [
            'entity' => ['*'],
        ]);
    }

    public function storePartialEntity(): ?JsonResponse
    {
        return $this->handleCommand(Create::class, [
            'entity' => ['id', 'title'],
        ]);
    }

    public function storeNoEntity(): ?JsonResponse
    {
        return $this->handleCommand(Create::class);
    }

    public function noHandle(): ?JsonResponse
    {
        return $this->handleCommand(NoHandle::class);
    }

    public function update(Request $request): ?JsonResponse
    {
        return $this->handleCommand(Change::class);
    }

    public function stopPropagation(Request $request): ?JsonResponse
    {
        return $this->handleCommand(StopPropagation::class);
    }
}
