<?php

declare(strict_types=1);

use Rector\Php72\Rector\Unset_\UnsetCastRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(UnsetCastRector::class);
};
