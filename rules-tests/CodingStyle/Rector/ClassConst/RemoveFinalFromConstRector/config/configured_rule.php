<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\ClassConst\RemoveFinalFromConstRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(RemoveFinalFromConstRector::class);
};
