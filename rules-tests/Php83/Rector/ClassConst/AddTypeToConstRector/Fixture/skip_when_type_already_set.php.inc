<?php

namespace Rector\Tests\Php83\Rector\ClassConst\AddTypeToConstRector\Fixture;

final class SkipWhenTypeAlreadySet
{
    public const string|int string = 'A';
}

?>
