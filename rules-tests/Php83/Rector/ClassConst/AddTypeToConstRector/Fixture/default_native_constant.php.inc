<?php

namespace Rector\Tests\Php83\Rector\ClassConst\AddTypeToConstRector\Fixture;

final class DefaultNativeConstant
{
    public const FOO = PHP_INT_MAX;
}

?>
-----
<?php

namespace Rector\Tests\Php83\Rector\ClassConst\AddTypeToConstRector\Fixture;

final class DefaultNativeConstant
{
    public const int FOO = PHP_INT_MAX;
}

?>
