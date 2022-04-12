<?php

declare(strict_types=1);

use Rector\DowngradePhp56\Rector\Pow\DowngradeExponentialOperatorRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(DowngradeExponentialOperatorRector::class);
};
