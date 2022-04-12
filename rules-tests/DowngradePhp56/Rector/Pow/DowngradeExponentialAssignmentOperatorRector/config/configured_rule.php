<?php

declare(strict_types=1);

use Rector\DowngradePhp56\Rector\Pow\DowngradeExponentialAssignmentOperatorRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(DowngradeExponentialAssignmentOperatorRector::class);
};
