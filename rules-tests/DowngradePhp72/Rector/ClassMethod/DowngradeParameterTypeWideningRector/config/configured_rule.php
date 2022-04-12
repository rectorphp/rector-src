<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;
use Rector\DowngradePhp72\Rector\ClassMethod\DowngradeParameterTypeWideningRector;
use Rector\Tests\DowngradePhp72\Rector\ClassMethod\DowngradeParameterTypeWideningRector\Fixture\SomeContainerInterface;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(DowngradeParameterTypeWideningRector::class)
        ->configure([
            ContainerInterface::class => ['set', 'get', 'has', 'initialized'],
            SomeContainerInterface::class => ['set', 'has'],
        ]);
};
