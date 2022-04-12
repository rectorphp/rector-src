<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\Assign\PHPStormVarAnnotationRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(PHPStormVarAnnotationRector::class);
};
