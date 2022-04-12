<?php

declare(strict_types=1);

use Rector\Tests\Transform\Rector\Class_\MergeInterfacesRector\Source\SomeInterface;
use Rector\Tests\Transform\Rector\Class_\MergeInterfacesRector\Source\SomeOldInterface;
use Rector\Transform\Rector\Class_\MergeInterfacesRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(MergeInterfacesRector::class)
        ->configure([
            SomeOldInterface::class => SomeInterface::class,
        ]);
};
