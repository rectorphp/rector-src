<?php

namespace Rector\Tests\CodeQuality\Rector\ClassMethod\ExplicitReturnNullRector\Fixture;

class SkipConstruct
{
    public function __construct(int $number)
    {
        if ($number > 50) {
            return 'yes';
        }

        echo 'test';
    }
}