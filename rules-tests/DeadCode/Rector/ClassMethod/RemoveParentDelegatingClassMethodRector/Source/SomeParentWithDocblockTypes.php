<?php

declare(strict_types=1);

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveParentDelegatingClassMethodRector\Source;

abstract class SomeParentWithDocblockTypes
{
    /**
     * @param int $value
     * @return int
     */
    public function compute($value)
    {
        return $value;
    }
}
