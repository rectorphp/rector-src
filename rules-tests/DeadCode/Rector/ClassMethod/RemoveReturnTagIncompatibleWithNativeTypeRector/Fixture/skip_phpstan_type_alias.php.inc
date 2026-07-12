<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveReturnTagIncompatibleWithNativeTypeRector\Fixture;

/**
 * @phpstan-type Config array{
 *     key: string,
 *     hash: string,
 * }
 */
final class SkipPHPStanTypeAlias
{
    /**
     * @return Config
     */
    public function run(bool $param): array
    {
        return [
            'key' => 'my-key',
            'hash' => 'my-hash',
        ];
    }
}
