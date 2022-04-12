<?php

declare(strict_types=1);

use Rector\Tests\Transform\Rector\MethodCall\CallableInMethodCallToVariableRector\Source\DummyCache;
use Rector\Transform\Rector\MethodCall\CallableInMethodCallToVariableRector;
use Rector\Transform\ValueObject\CallableInMethodCallToVariable;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(CallableInMethodCallToVariableRector::class)
        ->configure([new CallableInMethodCallToVariable(DummyCache::class, 'save', 1)]);
};
