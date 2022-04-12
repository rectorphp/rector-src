<?php

declare(strict_types=1);

use Rector\DowngradePhp74\Rector\ClassMethod\DowngradeCovariantReturnTypeRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(DowngradeCovariantReturnTypeRector::class);
};
