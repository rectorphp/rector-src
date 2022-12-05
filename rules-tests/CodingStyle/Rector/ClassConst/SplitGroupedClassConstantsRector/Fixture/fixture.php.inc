<?php

namespace Rector\Tests\CodingStyle\Rector\ClassConst\SplitGroupedClassConstantsRector\Fixture;

class Fixture
{
    const HI = true, AHOJ = 'true';
}

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\ClassConst\SplitGroupedClassConstantsRector\Fixture;

class Fixture
{
    const HI = true;
    const AHOJ = 'true';
}

?>
