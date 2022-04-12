<?php

declare(strict_types=1);

use Rector\DowngradePhp80\Rector\MethodCall\DowngradeReflectionPropertyGetDefaultValueRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(DowngradeReflectionPropertyGetDefaultValueRector::class);
};
