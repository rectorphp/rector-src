<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\If_\SimplifyIfReturnBoolRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(SimplifyIfReturnBoolRector::class);
};
