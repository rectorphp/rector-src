<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveReturnTagIncompatibleWithNativeTypeRector\Fixture;

/**
 * @phpstan-type Config array{key: string}
 */
final class SkipPHPStanTypeAliasInUnion
{
    /**
     * @return Config|null
     */
    public function provide(): ?array
    {
        return null;
    }
}
