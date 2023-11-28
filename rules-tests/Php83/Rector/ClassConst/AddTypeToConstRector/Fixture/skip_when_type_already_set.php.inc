<?php

namespace Rector\Tests\Php83\Rector\ClassConst\AddTypeToConstRector\Fixture;

final class SomeClass
{
    public const string|int string = 'A';
}

?>
