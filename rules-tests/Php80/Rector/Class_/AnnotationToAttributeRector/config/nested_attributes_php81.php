<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Rector\Core\ValueObject\PhpVersionFeature;
use Rector\Php80\ValueObject\AnnotationToAttribute;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    // covers https://wiki.php.net/rfc/new_in_initializers#nested_attributes
    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::PHP_VERSION_FEATURES, PhpVersionFeature::NEW_INITIALIZERS);

    $services = $containerConfigurator->services();
    $services->set(\Rector\Php80\Rector\Class_\AnnotationToAttributeRector::class)
        ->call('configure', [[
            \Rector\Php80\Rector\Class_\AnnotationToAttributeRector::ANNOTATION_TO_ATTRIBUTE => \Symplify\SymfonyPhpConfig\ValueObjectInliner::inline([
                new AnnotationToAttribute(
                    \Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\SourcePhp81\All::class,
                    \Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\SourcePhp81\Length::class,
                    \Rector\Tests\Php80\Rector\Class_\AnnotationToAttributeRector\SourcePhp81\NotNull::class,
                ),
            ]),
        ]]);
};
