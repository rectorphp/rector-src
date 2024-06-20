<?php

namespace Rector\Tests\Php83\Rector\ClassConst\AddTypeToConstRector\Fixture;

class ApplyTypeWhenNotOverridden
{
    public const STRING = 'some_type';
    public const INT = 1;
    protected const bool = true;
    protected const array = [];
}

class Child extends ApplyTypeWhenNotOverridden
{
    public const STRING = 'some_type';
    protected const bool = true;
}

?>
-----
<?php

namespace Rector\Tests\Php83\Rector\ClassConst\AddTypeToConstRector\Fixture;

class ApplyTypeWhenNotOverridden
{
    public const STRING = 'some_type';
    public const int INT = 1;
    protected const bool = true;
    protected const array array = [];
}

class Child extends ApplyTypeWhenNotOverridden
{
    public const STRING = 'some_type';
    protected const bool = true;
}

?>
