<?php

namespace Rector\Core\Tests\Issues\RemoveUnusedPrivateDuplicatedIf;

final class SkipUsedInArrayMap
{
    public function run(): array
    {
        return array_map(
            static fn (string $bar): string => self::returnFoo(),
            ['bar']
        );
    }

    private static function returnFoo(): string
    {
        return 'foo';
    }
}
