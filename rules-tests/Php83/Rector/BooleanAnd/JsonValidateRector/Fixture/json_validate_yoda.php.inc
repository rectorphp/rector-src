<?php

namespace Rector\Tests\Php83\Rector\BooleanAnd\JsonValidateRector\Fixture;

if (null !== json_decode($json) && JSON_ERROR_NONE === json_last_error()){
    echo 2;
}
?>
-----
<?php

namespace Rector\Tests\Php83\Rector\BooleanAnd\JsonValidateRector\Fixture;

if (json_validate($json)){
    echo 2;
}
?>