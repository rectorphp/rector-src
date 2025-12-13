<?php

namespace Rector\Tests\Privatization\Rector\ClassConst\PrivatizeFinalClassConstantRector\Fixture;

use Rector\Tests\Privatization\Rector\ClassConst\PrivatizeFinalClassConstantRector\Source\SomeTraitWithConst;

final class SkipTraitUsed
{
    use SomeTraitWithConst;

    protected const PROTECTED_TRAIT_CONST = 3;
}
