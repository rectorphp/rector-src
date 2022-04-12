<?php

declare(strict_types=1);

use Rector\Symfony\Rector\ClassMethod\TemplateAnnotationToThisRenderRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(TemplateAnnotationToThisRenderRector::class);
};
