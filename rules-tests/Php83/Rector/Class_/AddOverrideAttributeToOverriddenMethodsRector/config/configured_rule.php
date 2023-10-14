<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php83\Rector\Class_\AddOverrideAttributeToOverriddenMethodsRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(AddOverrideAttributeToOverriddenMethodsRector::class);
};
