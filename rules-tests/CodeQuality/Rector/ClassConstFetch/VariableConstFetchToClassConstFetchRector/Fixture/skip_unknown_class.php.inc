<?php

declare(strict_types=1);

namespace Rector\Tests\CodeQuality\Rector\ClassConstFetch\VariableConstFetchToClassConstFetchRector\Fixture;

final class SkipUnknownClass
{
    public function run($classWithConstants)
    {
        return $classWithConstants::ORIGINAL_VALUE;
    }
}
