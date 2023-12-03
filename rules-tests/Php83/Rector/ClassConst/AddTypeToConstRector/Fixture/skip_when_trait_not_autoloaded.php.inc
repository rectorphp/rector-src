<?php

namespace Rector\Tests\Php83\Rector\ClassConst\AddTypeToConstRector\Fixture;

final class SkipExtendsNotAutoloadedClass
{
    use NotAutoloadedTrait;

    public const STRING = 'some_type';
}

?>

