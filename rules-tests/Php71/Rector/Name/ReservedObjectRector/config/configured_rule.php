<?php

declare(strict_types=1);

use Rector\Php71\Rector\Name\ReservedObjectRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(ReservedObjectRector::class)
        ->configure([
            'ReservedObject' => 'SmartObject',
            'Object' => 'AnotherSmartObject',
        ]);
};
