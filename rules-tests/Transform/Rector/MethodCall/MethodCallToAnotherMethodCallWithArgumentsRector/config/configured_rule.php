<?php

declare(strict_types=1);

use Rector\Tests\Transform\Rector\MethodCall\MethodCallToAnotherMethodCallWithArgumentsRector\Source\NetteServiceDefinition;
use Rector\Transform\Rector\MethodCall\MethodCallToAnotherMethodCallWithArgumentsRector;
use Rector\Transform\ValueObject\MethodCallToAnotherMethodCallWithArguments;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(MethodCallToAnotherMethodCallWithArgumentsRector::class)
        ->configure([
            new MethodCallToAnotherMethodCallWithArguments(
                NetteServiceDefinition::class,
                'setInject',
                'addTag',
                ['inject']
            ),
        ]);
};
