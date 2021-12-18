<?php

namespace TestApp\Commands\Value;

use Carbon\Carbon;

trait CarbonValue
{
    public static function toCarbon($value): Carbon
    {
        return Carbon::parse($value);
    }

    public static function fromCarbon(Carbon $date): string
    {
        return $date->toAtomString();
    }
}
