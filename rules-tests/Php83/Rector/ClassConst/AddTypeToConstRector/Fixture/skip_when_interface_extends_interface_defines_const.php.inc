<?php

namespace Rector\Tests\Php83\Rector\ClassConst\AddTypeToConstRector\Fixture;

use Rector\Tests\Php83\Rector\ClassConst\AddTypeToConstRector\Source\SomeInterface;
use Rector\Tests\Php83\Rector\ClassConst\AddTypeToConstRector\Source\SomeOtherInterface;

interface SkipOnInterfaceExtendsInterface extends SomeInterface, SomeOtherInterface
{
    public const STRING = 'some_type';
}

?>

