<?php

declare(strict_types=1);

use Rector\Transform\Rector\PropertyFetch\ClassPropertyFetchToClassMethodCallRector;
use Rector\Transform\ValueObject\ClassPropertyFetchToClassMethodCall;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\SymfonyPhpConfig\ValueObjectInliner;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(Rector\Transform\Rector\PropertyFetch\ClassPropertyFetchToClassMethodCallRector::class)
        ->call(
            'configure',
            [
                [
                    ClassPropertyFetchToClassMethodCallRector::CLASS_PROPERTIES_TO_CLASS_METHOD_CALLS => ValueObjectInliner::inline(
                        [
                            new ClassPropertyFetchToClassMethodCall(
                                'Rector\Tests\Transform\Rector\PropertyFetch\ClassPropertyFetchToClassMethodCallRector\Source\SomeOtherClass',
                                'property',
                                'method'
                            ),
                        ]
                    ),
                ],
            ]
        );
};
