<?php

use Rector\Config\RectorConfig;
use Rector\Php80\Rector\ClassMethod\AddParamBasedOnParentClassMethodRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([__DIR__ . '/src']);

    $rectorConfig->bootstrapFiles(['bootstrap.php']);

    $rectorConfig->rule(AddParamBasedOnParentClassMethodRector::class);
};
