<?php

declare(strict_types=1);

use Rector\Tests\Transform\Rector\MethodCall\MethodCallToStaticCallRector\Source\AnotherDependency;
use Rector\Transform\Rector\MethodCall\MethodCallToStaticCallRector;
use Rector\Transform\ValueObject\MethodCallToStaticCall;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(MethodCallToStaticCallRector::class)
        ->configure([
            new MethodCallToStaticCall(AnotherDependency::class, 'process', 'StaticCaller', 'anotherMethod'),

        ]);
};
