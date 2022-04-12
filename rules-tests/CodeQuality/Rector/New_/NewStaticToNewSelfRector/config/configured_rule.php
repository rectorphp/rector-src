<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\New_\NewStaticToNewSelfRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(NewStaticToNewSelfRector::class);
};
