<?php

namespace Rector\Tests\Php83\Rector\ClassConst\AddTypeToConstRector\Fixture;

use Rector\Tests\Php83\Rector\ClassConst\AddTypeToConstRector\Source\SomeInterface;

final class SkipOnInterfaceImplements implements SomeInterface
{
    public const STRING = 'some_type';
}

