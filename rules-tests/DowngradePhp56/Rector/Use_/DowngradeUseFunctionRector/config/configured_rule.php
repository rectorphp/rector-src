<?php

declare(strict_types=1);

use Rector\DowngradePhp56\Rector\Use_\DowngradeUseFunctionRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(DowngradeUseFunctionRector::class);
};
