<?php

namespace Rector\Tests\CodeQuality\Rector\ClassConstFetch\ReplaceProductionConstantsWithLiteralsInTestFilesRector\Fixture;

final class SkipAllowedPattern extends \PHPUnit\Framework\TestCase
{
    public function baz(): void
    {
        echo SomeConstantWithAllowedConstantPattern::FOO;
    }
}

final class SomeConstantWithAllowedConstantPattern {
    public const FOO = 1;
}
