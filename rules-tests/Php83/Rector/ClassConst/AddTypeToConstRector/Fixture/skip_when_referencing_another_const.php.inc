<?php

namespace Rector\Tests\Php83\Rector\ClassConst\AddTypeToConstRector\Fixture;

use Rector\Tests\Php83\Rector\ClassConst\AddTypeToConstRector\Source\ParentClass;

class SomeClass
{
    public const STRING = ParentClass::STRING_OR_INT;
}

?>
