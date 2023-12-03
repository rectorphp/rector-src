<?php

namespace Rector\Tests\CodeQuality\Rector\BooleanAnd\SimplifyEmptyArrayCheckRector\Fixture;

final class LeftIsObjectRightInstanceof
{
    public function run($obj)
    {
        return is_object($obj) && $obj instanceof \DateTime;
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\BooleanAnd\SimplifyEmptyArrayCheckRector\Fixture;

final class LeftIsObjectRightInstanceof
{
    public function run($obj)
    {
        return $obj instanceof \DateTime;
    }
}

?>
