<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Equal\UseIdenticalOverEqualWithSameTypeRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(UseIdenticalOverEqualWithSameTypeRector::class);
};
