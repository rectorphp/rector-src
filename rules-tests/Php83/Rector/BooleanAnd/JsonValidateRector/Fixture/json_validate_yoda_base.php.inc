<?php

namespace Rector\Tests\Php83\Rector\BooleanAnd\JsonValidateRector\Fixture;

if (null !== json_decode($json) && json_last_error() === JSON_ERROR_NONE){
    echo 1;
}
?>
-----
<?php

namespace Rector\Tests\Php83\Rector\BooleanAnd\JsonValidateRector\Fixture;

if (json_validate($json)){
    echo 1;
}
?>