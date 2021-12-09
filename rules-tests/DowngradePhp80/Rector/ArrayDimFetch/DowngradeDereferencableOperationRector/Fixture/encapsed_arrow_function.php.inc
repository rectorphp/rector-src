<?php

namespace Rector\Tests\DowngradePhp80\Rector\ArrayDimFetch\DowngradeDereferencableOperationRector\Fixture;

function run()
{
    return fn (string $str) => "$str"[0];
}

?>
-----
<?php

namespace Rector\Tests\DowngradePhp80\Rector\ArrayDimFetch\DowngradeDereferencableOperationRector\Fixture;

function run()
{
    return fn (string $str) => ("$str")[0];
}

?>
