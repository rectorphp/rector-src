<?php

namespace Rector\Tests\CodeQuality\Rector\BooleanAnd\SimplifyEmptyArrayCheckRector\Fixture;

final class LeftInstanceofRightIsObject
{
    public function run($obj)
    {
        return $obj instanceof \DateTime && is_object($obj);
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\BooleanAnd\SimplifyEmptyArrayCheckRector\Fixture;

final class LeftInstanceofRightIsObject
{
    public function run($obj)
    {
        return $obj instanceof \DateTime;
    }
}

?>
