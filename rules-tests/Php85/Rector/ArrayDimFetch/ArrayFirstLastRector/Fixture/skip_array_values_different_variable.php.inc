<?php

declare(strict_types=1);

namespace Rector\Tests\Php85\Rector\ArrayDimFetch\ArrayFirstLastRector\Fixture;

final class SkipArrayValuesDifferentVariable
{
    public function run(array $array, array $differentVariable)
    {
        echo array_values($array)[count($differentVariable) - 1];
    }
}
