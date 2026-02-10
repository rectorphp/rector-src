<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php85\Rector\Property\AddOverrideAttributeToOverriddenPropertiesRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(AddOverrideAttributeToOverriddenPropertiesRector::class);
};
