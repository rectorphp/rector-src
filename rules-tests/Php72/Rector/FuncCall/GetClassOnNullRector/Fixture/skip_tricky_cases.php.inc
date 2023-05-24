<?php

namespace Rector\Tests\Php72\Rector\FuncCall\GetClassOnNullRector\Fixture;

final class SkipTrickyCases
{
    public function getItem($value)
    {
        $getClass = $value !== null ? get_class($value) : self::class;
        $getClass = null !== $value ? get_class($value) : self::class;

        $getClass = $value !== null ? get_class($value) : false;

        $getClass = $value === '123' ? get_class($value) : self::class;
        $getClass = '123' === $value ? get_class($value) : self::class;

        // change this
        $getClass = $value === null ? get_class($value) : self::class;
    }
}
