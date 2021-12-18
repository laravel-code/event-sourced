<?php

namespace TestApp\Commands\Posts;

use Carbon\Carbon;
use TestApp\Commands\Value\CarbonValue;

class WithObjects extends AbstractCommand
{
    use CarbonValue;

    public function __construct(
        public string $id,
        public Carbon $date,
    ) {
    }
}
