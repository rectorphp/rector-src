<?php

declare(strict_types=1);

use Rector\DowngradePhp80\Rector\Class_\DowngradeAttributeToAnnotationRector;
use Rector\DowngradePhp80\ValueObject\DowngradeAttributeToAnnotation;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(DowngradeAttributeToAnnotationRector::class)
        ->configure([
            new DowngradeAttributeToAnnotation(
                'Symfony\Component\Routing\Annotation\Route',
                'Symfony\Component\Routing\Annotation\Route'
            ),
            new DowngradeAttributeToAnnotation('Symfony\Contracts\Service\Attribute\Required', 'required'),
            new DowngradeAttributeToAnnotation('Attribute', 'Attribute'),
        ]);
};
