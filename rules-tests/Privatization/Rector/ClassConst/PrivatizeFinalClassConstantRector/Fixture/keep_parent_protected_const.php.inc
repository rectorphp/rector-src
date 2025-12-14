<?php

namespace Rector\Tests\Privatization\Rector\ClassConst\PrivatizeFinalClassConstantRector\Fixture;

use Rector\Tests\Privatization\Rector\ClassConst\PrivatizeFinalClassConstantRector\Source\AbstractClassWithProtectedClassConst;

final class KeepParentProtectedConst extends AbstractClassWithProtectedClassConst
{
    protected const PROTECTED_CONST = 5;
}
