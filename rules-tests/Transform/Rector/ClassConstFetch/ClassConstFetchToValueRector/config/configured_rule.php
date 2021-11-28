<?php

declare(strict_types=1);

use Rector\Tests\Transform\Rector\ClassConstFetch\ClassConstFetchToValueRector\Source\OldClassWithConstants;
use Rector\Transform\Rector\ClassConstFetch\ClassConstFetchToValueRector;
use Rector\Transform\ValueObject\ClassConstFetchToValue;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\SymfonyPhpConfig\ValueObjectInliner;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(ClassConstFetchToValueRector::class)
        ->configure([
            new ClassConstFetchToValue(OldClassWithConstants::class, 'DEVELOPMENT', 'development'),
            new ClassConstFetchToValue(OldClassWithConstants::class, 'PRODUCTION', 'production'),
        ]);
};
