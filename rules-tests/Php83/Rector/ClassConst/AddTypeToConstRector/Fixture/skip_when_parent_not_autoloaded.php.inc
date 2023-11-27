<?php

namespace Rector\Tests\Php83\Rector\ClassConst\AddTypeToConstRector\Fixture;

class SkipExtendsNotAutoloadedClass extends NotAutoloadedClass
{
    public const STRING = 'some_type';
}

?>

