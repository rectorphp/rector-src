<?php

declare(strict_types=1);

namespace Rector\Tests\Php85\Rector\ArrayDimFetch\ArrayFirstLastRector\Fixture;

final class SkipDifferentVariable
{
    public function run(array $array, array $differentVariable)
    {
        echo $array[array_key_first($differentVariable)];
        echo $array[array_key_last($differentVariable)];
    }
}
