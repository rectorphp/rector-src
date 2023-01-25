<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnNeverTypeRector\FixturePHP72;

function run($key)
{
    if ($key) {
        echo 100;
        exit;
    }
}
