<?php

declare(strict_types=1);

use Rector\DowngradePhp80\Rector\Instanceof_\DowngradePhp80ResourceReturnToObjectRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(DowngradePhp80ResourceReturnToObjectRector::class);
};
