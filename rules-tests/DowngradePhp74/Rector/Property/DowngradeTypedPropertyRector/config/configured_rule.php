<?php

declare(strict_types=1);

use Rector\DowngradePhp74\Rector\Property\DowngradeTypedPropertyRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(DowngradeTypedPropertyRector::class);
};
