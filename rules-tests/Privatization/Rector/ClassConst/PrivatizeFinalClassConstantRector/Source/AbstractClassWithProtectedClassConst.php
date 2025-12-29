<?php

declare(strict_types=1);

namespace Rector\Tests\Privatization\Rector\ClassConst\PrivatizeFinalClassConstantRector\Source;

abstract class AbstractClassWithProtectedClassConst
{
    protected const PROTECTED_CONST = 3;
}
