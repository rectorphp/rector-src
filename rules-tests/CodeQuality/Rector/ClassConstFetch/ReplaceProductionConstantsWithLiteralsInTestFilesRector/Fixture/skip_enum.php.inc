<?php

namespace Rector\Tests\CodeQuality\Rector\ClassConstFetch\ReplaceProductionConstantsWithLiteralsInTestFilesRector\Fixture;

final class SkipAllowedPattern extends \PHPUnit\Framework\TestCase
{
    public function baz(): void
    {
        echo SomeEnum::FOO->value;
    }
}

enum SomeEnum: int {
    case FOO = 1;
}
