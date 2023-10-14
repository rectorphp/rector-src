<?php

namespace Rector\Tests\CodeQuality\Rector\Return_\SimplifyReturnExpressionToConstantTypeRector\Fixture;

class MultipleReturns
{
    public function run()
    {
        $return = false;

        if (rand(0,1)) {
            if (rand(2,1)) {
                return $return;
            }

            return true;
        }

        return $return;
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Return_\SimplifyReturnExpressionToConstantTypeRector\Fixture;

class MultipleReturns
{
    public function run()
    {
        $return = false;

        if (rand(0,1)) {
            if (rand(2,1)) {
                return false;
            }

            return true;
        }

        return false;
    }
}

?>
