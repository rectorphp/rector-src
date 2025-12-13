<?php

declare(strict_types=1);

namespace Rector\Tests\Privatization\Rector\ClassConst\PrivatizeFinalClassConstantRector\Source;

trait SomeTraitWithConst
{
    protected const PROTECTED_TRAIT_CONST = 3;
}
