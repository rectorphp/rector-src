<?php

namespace Rector\Tests\CodeQuality\Rector\Return_\SimplifyReturnExpressionToConstantTypeRector\Fixture;

class SomeClass
{
    public function run()
    {
        $apcInstalled = false;

        if (rand(0,1)) {
            return true;
        }

        return $apcInstalled;
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Return_\SimplifyReturnExpressionToConstantTypeRector\Fixture;

class SomeClass
{
    public function run()
    {
        $apcInstalled = false;

        if (rand(0,1)) {
            return true;
        }

        return false;
    }
}

?>
