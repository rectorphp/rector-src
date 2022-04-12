<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\ClassMethod\NarrowUnionTypeDocRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(NarrowUnionTypeDocRector::class);
};
