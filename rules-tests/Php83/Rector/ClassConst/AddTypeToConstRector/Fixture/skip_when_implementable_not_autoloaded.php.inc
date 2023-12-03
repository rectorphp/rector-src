<?php

namespace Rector\Tests\Php83\Rector\ClassConst\AddTypeToConstRector\Fixture;

final class SkipExtendsNotAutoloadedInterfaceClass implements NotAutoloadedInterface
{
    public const STRING = 'some_type';
}

?>

