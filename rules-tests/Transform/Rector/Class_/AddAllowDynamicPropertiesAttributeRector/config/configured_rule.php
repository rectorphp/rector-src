<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\Transform\Rector\Class_\AddAllowDynamicPropertiesAttributeRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->phpVersion(PhpVersionFeature::DEPRECATE_DYNAMIC_PROPERTIES);

    $rectorConfig->ruleWithConfiguration(AddAllowDynamicPropertiesAttributeRector::class, [
        '*\Fixture\Process\*',
        'Rector\Tests\Transform\Rector\Class_\AddAllowDynamicPropertiesAttributeRector\Fixture\DefinedClass\AddAllowDynamicProperties',
    ]);
};
