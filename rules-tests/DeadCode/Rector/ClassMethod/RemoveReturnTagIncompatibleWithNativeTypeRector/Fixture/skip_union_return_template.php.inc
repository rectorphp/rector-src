<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveReturnTagIncompatibleWithNativeTypeRector\Fixture;

final class SkipUnionReturnTemplate
{
    /**
     * @template T of array<string>
     * @param T|null $value
     * @return T|null
     */
    public function run(?array $value): ?array
    {
        return $value;
    }
}
