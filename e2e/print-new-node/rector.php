<?php

use Rector\Config\RectorConfig;
use Rector\Php80\Rector\ClassMethod\AddParamBasedOnParentClassMethodRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__ . '/src/TestClass.php',
        __DIR__ . '/src/ExtendingTestClass.php',
    ]);

    $rectorConfig->rule(AddParamBasedOnParentClassMethodRector::class);
};