<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Class_\DynamicDocBlockPropertyToNativePropertyRector;
use Rector\Config\RectorConfig;
use Rector\ValueObject\PhpVersionFeature;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->phpVersion(PhpVersionFeature::DEPRECATE_DYNAMIC_PROPERTIES);

    $rectorConfig->rule(DynamicDocBlockPropertyToNativePropertyRector::class);
};
