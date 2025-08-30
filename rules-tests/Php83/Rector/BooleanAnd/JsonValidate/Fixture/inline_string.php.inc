<?php
namespace Rector\Tests\Php83\Rector\BooleanAnd\JsonValidate\Fixture;

if (json_decode('{"a":1}', true) !== null && json_last_error() === JSON_ERROR_NONE) {
    echo "inline";
}
-----
<?php
namespace Rector\Tests\Php83\Rector\BooleanAnd\JsonValidate\Fixture;

if (json_validate('{"a":1}')) {
    echo "inline";
}