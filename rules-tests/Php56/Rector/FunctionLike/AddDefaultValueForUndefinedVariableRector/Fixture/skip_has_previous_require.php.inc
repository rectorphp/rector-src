<?php

namespace Rector\Tests\Php56\Rector\FunctionLike\AddDefaultValueForUndefinedVariableRector\Fixture;

class SkipHasPreviousRequire
{
    public function run()
    {
        require __DIR__ . '/connection.php';
        $conn->execute();
    }
}
