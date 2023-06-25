<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php82\Rector\Param\AddSensitiveParameterAttributeRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(AddSensitiveParameterAttributeRector::class, [
        AddSensitiveParameterAttributeRector::SENSITIVE_PARAMETERS => ['password'],
    ]);
};
