<?php

namespace Rector\Tests\Php83\Rector\ClassConst\AddTypeToConstRector\FixtureMarkedAsFinal;

class NormalClassAsFinal
{
    public const A = 'A';
}

?>
-----
<?php

namespace Rector\Tests\Php83\Rector\ClassConst\AddTypeToConstRector\FixtureMarkedAsFinal;

class NormalClassAsFinal
{
    public const string A = 'A';
}

?>