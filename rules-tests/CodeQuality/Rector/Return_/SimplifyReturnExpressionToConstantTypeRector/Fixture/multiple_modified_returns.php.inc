<?php

namespace Rector\Tests\CodeQuality\Rector\Return_\SimplifyReturnExpressionToConstantTypeRector\Fixture;

class MultipleModifiedReturns
{
    public function run()
    {
        $return = false;

        if (rand(0,1)) {
            $return = null;

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

class MultipleModifiedReturns
{
    public function run()
    {
        $return = false;

        if (rand(0,1)) {
            $return = null;

            if (rand(2,1)) {
                return null;
            }

            return true;
        }

        return false;
    }
}

?>
