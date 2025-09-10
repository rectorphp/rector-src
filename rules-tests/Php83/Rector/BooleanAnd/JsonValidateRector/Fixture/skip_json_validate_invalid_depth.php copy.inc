<?php

namespace Rector\Tests\Php83\Rector\BooleanAnd\JsonValidateRector\Fixture;

if (json_decode($json, true, -2) !== null && json_last_error() === JSON_ERROR_NONE){
    echo 1;
}
?>