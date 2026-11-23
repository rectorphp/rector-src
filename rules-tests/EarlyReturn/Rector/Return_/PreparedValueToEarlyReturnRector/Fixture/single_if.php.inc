<?php

namespace Rector\Tests\EarlyReturn\Rector\Return_\PreparedValueToEarlyReturnRector\Fixture;

class SingleIf
{
    public function run($var)
    {
        $var = null;
        if (rand(0,1)) {
            $var = 1 ;
        }

        return $var;
    }
}

?>
-----
<?php

namespace Rector\Tests\EarlyReturn\Rector\Return_\PreparedValueToEarlyReturnRector\Fixture;

class SingleIf
{
    public function run($var)
    {
        if (rand(0,1)) {
            return 1;
        }

        return null;
    }
}

?>
