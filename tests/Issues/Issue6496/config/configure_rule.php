<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Symfony\Symfony34\ClassMethod\ReplaceSensioRouteAnnotationWithSymfonyRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->importNames();
    $rectorConfig->rule(ReplaceSensioRouteAnnotationWithSymfonyRector::class);
};
