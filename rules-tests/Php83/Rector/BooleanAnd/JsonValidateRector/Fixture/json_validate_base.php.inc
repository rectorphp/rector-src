<?php

namespace Rector\Tests\Php83\Rector\BooleanAnd\JsonValidateRector\Fixture;

if (json_decode($json, true, 512) !== null && json_last_error() === JSON_ERROR_NONE){
    echo 1;
}
?>
-----
<?php

namespace Rector\Tests\Php83\Rector\BooleanAnd\JsonValidateRector\Fixture;

if (json_validate($json, true, 512)){
    echo 1;
}
?>