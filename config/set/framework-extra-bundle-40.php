<?php

declare(strict_types=1);

use Rector\Symfony\Rector\ClassMethod\RemoveServiceFromSensioRouteRector;
use Rector\Symfony\Rector\ClassMethod\ReplaceSensioRouteAnnotationWithSymfonyRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(ReplaceSensioRouteAnnotationWithSymfonyRector::class);

    $services->set(RemoveServiceFromSensioRouteRector::class);
};
