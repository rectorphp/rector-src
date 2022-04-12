<?php

declare(strict_types=1);

use Rector\Tests\Transform\Rector\MethodCall\MethodCallToMethodCallRector\Source\FirstDependency;
use Rector\Tests\Transform\Rector\MethodCall\MethodCallToMethodCallRector\Source\SecondDependency;
use Rector\Transform\Rector\MethodCall\MethodCallToMethodCallRector;
use Rector\Transform\ValueObject\MethodCallToMethodCall;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(MethodCallToMethodCallRector::class)
        ->configure([new MethodCallToMethodCall(FirstDependency::class, 'go', SecondDependency::class, 'away')]);
};
