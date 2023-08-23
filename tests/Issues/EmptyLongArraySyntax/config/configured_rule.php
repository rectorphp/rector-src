<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php73\Rector\ConstFetch\SensitiveConstantNameRector;
use Rector\Php80\Rector\ClassMethod\AddParamBasedOnParentClassMethodRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rules([
        SensitiveConstantNameRector::class,
        AddParamBasedOnParentClassMethodRector::class,
    ]);
};
