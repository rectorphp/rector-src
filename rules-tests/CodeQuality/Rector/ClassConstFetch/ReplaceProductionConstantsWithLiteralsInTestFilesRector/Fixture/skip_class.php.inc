<?php

namespace Rector\Tests\CodeQuality\Rector\ClassConstFetch\ReplaceProductionConstantsWithLiteralsInTestFilesRector\Fixture;

final class SkipClass
{
    public function baz(): void
    {
        echo SkipClass::class;
    }
}
