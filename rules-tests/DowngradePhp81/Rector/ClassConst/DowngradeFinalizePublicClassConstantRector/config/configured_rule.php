<?php

declare(strict_types=1);

use Rector\DowngradePhp81\Rector\ClassConst\DowngradeFinalizePublicClassConstantRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(DowngradeFinalizePublicClassConstantRector::class);
};
