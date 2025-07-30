<?php

declare(strict_types=1);

use PHPStan\Type\ObjectType;
use Rector\Config\RectorConfig;
use Rector\Transform\Rector\ArrayDimFetch\ArrayDimFetchToMethodCallRector;
use Rector\Transform\ValueObject\ArrayDimFetchToMethodCall;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig
        ->ruleWithConfiguration(ArrayDimFetchToMethodCallRector::class, [
            new ArrayDimFetchToMethodCall(new ObjectType('SomeClass'), 'get', 'set', 'has', 'unset'),
            new ArrayDimFetchToMethodCall(new ObjectType('Container'), 'get'),
        ]);
};
