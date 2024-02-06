<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php82\Rector\Class_\AddAllowDynamicPropertiesAttributeToClassMissingPropertiesRector;
use Rector\ValueObject\PhpVersionFeature;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(AddAllowDynamicPropertiesAttributeToClassMissingPropertiesRector::class);

    $rectorConfig->phpVersion(PhpVersionFeature::ALLOW_DYNAMIC_PROPERTIES_ATTRIBUTE);
};
