<?php

namespace Rector\Tests\CodingStyle\Rector\If_\NullableCompareToNullRector\Fixture;

function doFoo(?stdClass $value) {
    if ($value) {
    }

    if (!$value) {
    }
}

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\If_\NullableCompareToNullRector\Fixture;

function doFoo(?stdClass $value) {
    if ($value !== null) {
    }

    if ($value === null) {
    }
}

?>
