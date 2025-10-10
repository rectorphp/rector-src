<?php

declare(strict_types=1);

namespace Rector\Tests\CodeQuality\Rector\ClassConstFetch\VariableConstFetchToClassConstFetchRector\Fixture;

use Rector\Tests\CodeQuality\Rector\ClassConstFetch\VariableConstFetchToClassConstFetchRector\Source\ChildClassNotWithConstant;

final class SkipDocType
{
    /**
     * @param ChildClassNotWithConstant $classWithConstants
     */
    public function run($classWithConstants)
    {
        return $classWithConstants::ORIGINAL_VALUE;
    }
}
