<?php

declare(strict_types=1);

use Rector\Php74\Rector\Property\RestoreDefaultNullToNullableTypePropertyRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(RestoreDefaultNullToNullableTypePropertyRector::class);
};
