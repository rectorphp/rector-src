<?php

namespace Rector\Tests\Php83\Rector\ClassConst\AddTypeToConstRector\Fixture;

class Fixture
{
    public const VALUE = 1;
}

?>
-----
<?php

namespace Rector\Tests\Php83\Rector\ClassConst\AddTypeToConstRector\Fixture;

class Fixture
{
    public const int VALUE = 1;
}

?>
