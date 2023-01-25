<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnNeverTypeRector\Fixture;

function run($key)
{
    if ($key) {
        echo 100;
        exit;
    }
}
