<?php

declare(strict_types=1);

use Rector\Php74\Rector\Closure\ClosureToArrowFunctionRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(ClosureToArrowFunctionRector::class);
};
