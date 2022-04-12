<?php

declare(strict_types=1);

use Rector\DowngradePhp81\Rector\Instanceof_\DowngradePhp81ResourceReturnToObjectRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(DowngradePhp81ResourceReturnToObjectRector::class);
};
