<?php

declare(strict_types=1);

namespace Rector\Tests\Arguments\Rector\ClassMethod\ReplaceArgumentDefaultValueRector\Source;

class SomeMultiArg
{
    public function firstArgument($a = 1, $b = 2)
    {
    }

    public function secondArgument($a = 1, $b = 2, $c = 3)
    {
    }
}
