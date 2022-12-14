<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\ClassConstFetch\ConvertStaticPrivateConstantToSelfRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig
        ->ruleWithConfiguration(
            ConvertStaticPrivateConstantToSelfRector::class,
            [
                ConvertStaticPrivateConstantToSelfRector::ENABLE_FOR_NON_FINAL_CLASSES => true,
            ]
        );
};
