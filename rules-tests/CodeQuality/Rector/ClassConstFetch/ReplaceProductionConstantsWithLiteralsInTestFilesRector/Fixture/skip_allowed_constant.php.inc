<?php

namespace Rector\Tests\CodeQuality\Rector\ClassConstFetch\ReplaceProductionConstantsWithLiteralsInTestFilesRector\Fixture;

final class SkipAllowedConstant extends \PHPUnit\Framework\TestCase
{
    public function baz(): void
    {
        echo AllowedConstant::FOO;
    }
}

final class AllowedConstant {
    public const FOO = 1;
}
