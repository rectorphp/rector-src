<?php

declare(strict_types=1);

use Rector\DowngradePhp71\Rector\Array_\SymmetricArrayDestructuringToListRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(SymmetricArrayDestructuringToListRector::class);
};
