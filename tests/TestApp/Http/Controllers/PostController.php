<?php

namespace TestApp\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use LaravelCode\EventSouring\Http\Controller\HandleCommand;
use TestApp\Commands\Posts\Change;
use TestApp\Commands\Posts\Create;

class PostController extends Controller
{
    use HandleCommand;

    public function store()
    {
        return $this->handleCommand(Create::class, [
            'entity' => ['*'],
        ]);
    }

    public function storePartialEntity()
    {
        return $this->handleCommand(Create::class, [
            'entity' => ['id', 'title'],
        ]);
    }

    public function storeNoEntity()
    {
        return $this->handleCommand(Create::class);
    }

    public function noHandle()
    {
        return $this->handleCommand(Create::class);
    }

    public function update(Request $request)
    {
        return $this->handleCommand(Change::class);
    }
}
