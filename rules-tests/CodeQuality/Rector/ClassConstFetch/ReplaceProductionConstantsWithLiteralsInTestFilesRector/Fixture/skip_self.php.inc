<?php

namespace Rector\Tests\CodeQuality\Rector\ClassConstFetch\ReplaceProductionConstantsWithLiteralsInTestFilesRector\Fixture;

final class SkipSelf extends \PHPUnit\Framework\TestCase
{
    private const BAR = 1;

    public function baz(): void
    {
        echo self::BAR;
    }
}
