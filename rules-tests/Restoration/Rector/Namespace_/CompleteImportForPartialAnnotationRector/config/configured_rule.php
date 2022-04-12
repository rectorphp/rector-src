<?php

declare(strict_types=1);

use Rector\Restoration\Rector\Namespace_\CompleteImportForPartialAnnotationRector;
use Rector\Restoration\ValueObject\CompleteImportForPartialAnnotation;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(CompleteImportForPartialAnnotationRector::class)
        ->configure([new CompleteImportForPartialAnnotation('Doctrine\ORM\Mapping', 'ORM')]);
};
