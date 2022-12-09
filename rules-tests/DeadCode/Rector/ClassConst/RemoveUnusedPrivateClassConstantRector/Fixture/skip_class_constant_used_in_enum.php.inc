<?php

namespace Rector\Tests\DeadCode\Rector\ClassConst\RemoveUnusedPrivateClassConstantRector\Fixture;

enum SkipClassConstantUsedInEnum
{
    case Foo;
    case Baz;

    private const SOME_CONST = [
        self::Foo,
        self::Baz,
    ];

    public function run(): bool
    {
        return in_array($this, self::SOME_CONST, true);
    }
}
