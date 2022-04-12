<?php

declare(strict_types=1);

use Rector\DeadCode\Rector\ClassLike\RemoveAnnotationRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(RemoveAnnotationRector::class)
        ->configure(['method', 'JMS\DiExtraBundle\Annotation\InjectParams']);
};
