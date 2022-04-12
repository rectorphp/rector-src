<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\BooleanAnd\SimplifyEmptyArrayCheckRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(SimplifyEmptyArrayCheckRector::class);
};
