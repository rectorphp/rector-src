<?php

declare(strict_types=1);

use Rector\DowngradePhp71\Rector\String_\DowngradeNegativeStringOffsetToStrlenRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(DowngradeNegativeStringOffsetToStrlenRector::class);
};
