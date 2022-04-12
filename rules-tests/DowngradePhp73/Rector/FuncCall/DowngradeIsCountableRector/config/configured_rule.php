<?php

declare(strict_types=1);

use Rector\DowngradePhp73\Rector\FuncCall\DowngradeIsCountableRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(DowngradeIsCountableRector::class);
};
