<?php

namespace Rector\Tests\CodingStyle\Rector\ClassMethod\MakeInheritedMethodVisibilitySameAsParentRector\FixtureForPhp72;

use Rector\Tests\CodingStyle\Rector\ClassMethod\MakeInheritedMethodVisibilitySameAsParentRector\Source\ParentWithPublicConstructor;

final class SkipSelfCtor extends ParentWithPublicConstructor
{
    private function __construct()
    {
    }

    public static function create()
    {
        return new self();
    }
}
