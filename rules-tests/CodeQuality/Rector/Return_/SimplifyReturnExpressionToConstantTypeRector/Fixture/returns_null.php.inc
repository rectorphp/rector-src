<?php

namespace Rector\Tests\CodeQuality\Rector\Return_\SimplifyReturnExpressionToConstantTypeRector\Fixture;

class ReturnsNull
{
    public function run(?self $b)
    {
        if ($b) {
            if (rand(0,1)) {

            }

            return $b;
        }

        return $b;
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Return_\SimplifyReturnExpressionToConstantTypeRector\Fixture;

class ReturnsNull
{
    public function run(?self $b)
    {
        if ($b) {
            if (rand(0,1)) {

            }

            return $b;
        }

        return null;
    }
}

?>
