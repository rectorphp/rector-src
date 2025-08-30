<?php
namespace Rector\Tests\Php80\Rector\BooleanAnd\JsonValidate\Fixture;

if (json_decode($json, true) !== null && json_last_error() === JSON_ERROR_NONE){
    echo 1;
}
?>
-----
<?php
namespace Rector\Tests\Php80\Rector\BooleanAnd\JsonValidate\Fixture;

if (json_validate($json)){
    echo 1;
}
?>
