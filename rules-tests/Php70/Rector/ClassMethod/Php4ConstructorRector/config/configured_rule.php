<?php

declare(strict_types=1);

use Rector\Php70\Rector\ClassMethod\Php4ConstructorRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(Php4ConstructorRector::class);
};
