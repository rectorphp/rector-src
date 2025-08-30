<?php
namespace Rector\Tests\Php83\Rector\BooleanAnd\JsonValidateRector\Fixture;

if ($flag) {
    echo "skip";
} elseif (json_decode($config, true) !== null && json_last_error() === JSON_ERROR_NONE) {
    echo "valid config";
}
-----
<?php
namespace Rector\Tests\Php83\Rector\BooleanAnd\JsonValidateRector\Fixture;

if ($flag) {
    echo "skip";
} elseif (json_validate($config)) {
    echo "valid config";
}
