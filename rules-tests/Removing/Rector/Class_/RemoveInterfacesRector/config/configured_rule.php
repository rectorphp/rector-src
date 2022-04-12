<?php

declare(strict_types=1);

use Rector\Removing\Rector\Class_\RemoveInterfacesRector;
use Rector\Tests\Removing\Rector\Class_\RemoveInterfacesRector\Source\SomeInterface;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(RemoveInterfacesRector::class)
        ->configure([SomeInterface::class]);
};
