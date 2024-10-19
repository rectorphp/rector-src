<?php

namespace Rector\Tests\CodeQuality\Rector\If_\ExplicitBoolCompareRector\Fixture;

class IfAssignCond
{
    public function run()
    {
        if ($base64 = base64_encode('')) {
            return 5;
        }

        return '10';
    }
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\If_\ExplicitBoolCompareRector\Fixture;

class IfAssignCond
{
    public function run()
    {
        $base64 = base64_encode('');
        if ($base64 !== '' && $base64 !== '0') {
            return 5;
        }

        return '10';
    }
}

?>
