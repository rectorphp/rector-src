<?php

namespace Rector\Tests\Php83\Rector\ClassConst\AddTypeToConstRector\Fixture;

use Rector\Tests\Php83\Rector\ClassConst\AddTypeToConstRector\Source\ParentClass;

final class A
{
    public const A_STRING = B::B_STRING;
    public const A_INT= B::B_INT;
    public const A_STRING_OR_INT = ParentClass::STRING_OR_INT;
}

final class B
{
    public const B_STRING = C::C_STRING;
    public const B_INT = C::C_INT;
}

final class C
{
    public const C_STRING = 'C';
    public const C_INT = 42;
}

?>
-----
<?php

namespace Rector\Tests\Php83\Rector\ClassConst\AddTypeToConstRector\Fixture;

use Rector\Tests\Php83\Rector\ClassConst\AddTypeToConstRector\Source\ParentClass;

final class A
{
    public const string A_STRING = B::B_STRING;
    public const int A_INT= B::B_INT;
    public const string A_STRING_OR_INT = ParentClass::STRING_OR_INT;
}

final class B
{
    public const string B_STRING = C::C_STRING;
    public const int B_INT = C::C_INT;
}

final class C
{
    public const string C_STRING = 'C';
    public const int C_INT = 42;
}

?>
