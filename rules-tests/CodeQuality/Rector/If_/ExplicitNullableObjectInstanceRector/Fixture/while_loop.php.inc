<?php

namespace Rector\Tests\CodeQuality\Rector\If_\ExplicitNullableObjectInstanceRector\Fixture;

final class WhileLoop
{
    public function run(?\stdClass $item)
    {
        while ($item !== null) {
            $item = null;
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\If_\ExplicitNullableObjectInstanceRector\Fixture;

final class WhileLoop
{
    public function run(?\stdClass $item)
    {
        while ($item instanceof \stdClass) {
            $item = null;
        }
    }
}

?>
