<?php

namespace Rector\Tests\Php83\Rector\ClassConst\AddTypeToConstRector\Fixture;

use Rector\Tests\Php83\Rector\ClassConst\AddTypeToConstRector\Source\ParentClass;

class SomeClass extends ParentClass
{
    public const STRING_OR_INT = 'some_type';
}

?>

