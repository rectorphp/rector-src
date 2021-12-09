<?php

namespace Rector\Tests\DowngradePhp80\Rector\ArrayDimFetch\DowngradeDereferencableOperationRector\Fixture;

function getFirstChar(string $str, string $suffix = '')
{
    return "$str$suffix"[0];
}

?>
-----
<?php

namespace Rector\Tests\DowngradePhp80\Rector\ArrayDimFetch\DowngradeDereferencableOperationRector\Fixture;

function getFirstChar(string $str, string $suffix = '')
{
    return ("$str$suffix")[0];
}

?>
