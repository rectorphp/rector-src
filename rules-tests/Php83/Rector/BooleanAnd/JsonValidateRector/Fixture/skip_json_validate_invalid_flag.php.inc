<?php

namespace Rector\Tests\Php83\Rector\BooleanAnd\JsonValidateRector\Fixture;

if (json_decode(json: $json, associative: true, depth:512,  flags: 1) !== null && json_last_error() === JSON_ERROR_NONE){
    echo 1;
}
?>