<?php

namespace Rector\Tests\Php83\Rector\ClassConst\AddTypeToConstRector\Fixture;

final class PlusOrMinus
{
    public const MINUS_1 = -1;

    public const PLUS_1 = +1;
}

?>
-----
<?php

namespace Rector\Tests\Php83\Rector\ClassConst\AddTypeToConstRector\Fixture;

final class PlusOrMinus
{
    public const int MINUS_1 = -1;

    public const int PLUS_1 = +1;
}

?>
