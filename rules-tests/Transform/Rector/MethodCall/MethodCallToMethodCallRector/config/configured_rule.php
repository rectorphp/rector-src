<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Tests\Transform\Rector\MethodCall\MethodCallToMethodCallRector\Source\FirstDependency;
use Rector\Tests\Transform\Rector\MethodCall\MethodCallToMethodCallRector\Source\SecondDependency;
use Rector\Transform\Rector\MethodCall\MethodCallToMethodCallRector;
use Rector\Transform\ValueObject\MethodCallToMethodCall;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig
        ->ruleWithConfiguration(
            MethodCallToMethodCallRector::class,
            [new MethodCallToMethodCall(FirstDependency::class, 'go', SecondDependency::class, 'away')]
        );

    $rectorConfig->ruleWithConfiguration(
        MethodCallToMethodCallRector::class,
        [new MethodCallToMethodCall('Rector\Tests\Transform\Rector\MethodCall\MethodCallToMethodCallRector\Fixture\AClass', 'methodFromAClass', 'Rector\Tests\Transform\Rector\MethodCall\MethodCallToMethodCallRector\Fixture\AnotherClass', 'methodFromAnotherClass')]
    );
};
