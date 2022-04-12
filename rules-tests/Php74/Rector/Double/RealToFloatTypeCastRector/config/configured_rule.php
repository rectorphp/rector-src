<?php

declare(strict_types=1);

use Rector\Php74\Rector\Double\RealToFloatTypeCastRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(RealToFloatTypeCastRector::class);
};
