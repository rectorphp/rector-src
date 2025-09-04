<?php
namespace Rector\Tests\Php83\Rector\BooleanAnd\JsonValidateRector\Fixture;

if (json_decode(associative: true, json: $json) !== null && json_last_error() === JSON_ERROR_NONE){
    echo 1;
}
?>
-----
<?php
namespace Rector\Tests\Php83\Rector\BooleanAnd\JsonValidateRector\Fixture;

if (json_validate(associative: true, json: $json)){
    echo 1;
}
?>
