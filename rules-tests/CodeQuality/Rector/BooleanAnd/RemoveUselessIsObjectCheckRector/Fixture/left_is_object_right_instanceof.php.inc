<?php

namespace Rector\Tests\CodeQuality\Rector\BooleanAnd\RemoveUselessIsObjectCheckRector\Fixture;

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

namespace Rector\Tests\CodeQuality\Rector\BooleanAnd\RemoveUselessIsObjectCheckRector\Fixture;

final class LeftIsObjectRightInstanceof
{
    public function run($obj)
    {
        return $obj instanceof \DateTime;
    }
}

?>
