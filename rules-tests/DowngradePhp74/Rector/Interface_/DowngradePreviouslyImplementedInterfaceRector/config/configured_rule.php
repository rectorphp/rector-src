<?php

declare(strict_types=1);

use Rector\DowngradePhp74\Rector\Interface_\DowngradePreviouslyImplementedInterfaceRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(DowngradePreviouslyImplementedInterfaceRector::class);
};
