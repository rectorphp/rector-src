<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveReturnTagIncompatibleWithNativeTypeRector\Fixture;

final class SkipTemplatedType
{
    /**
     * @template T of array<string>
     * @param T $value
     * @return T
     */
    public function run(array $value): array
    {
        return $value;
    }
}
