<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php83\Rector\ClassConst\AddTypeToConstRector;
use Rector\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rules([AddOverrideAttributeToOverriddenMethodsRector::class, AddTypeToConstRector::class]);
};
