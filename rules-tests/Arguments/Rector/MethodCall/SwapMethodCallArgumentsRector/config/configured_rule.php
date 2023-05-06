<?php

declare(strict_types=1);

use Rector\Arguments\Rector\MethodCall\SwapMethodCallArgumentsRector;
use Rector\Arguments\ValueObject\SwapMethodCallArguments;
use Rector\Config\RectorConfig;
use Rector\Tests\Arguments\Rector\MethodCall\SwapMethodCallArgumentsRector\Source\MethodCaller;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(SwapMethodCallArgumentsRector::class, [
        new SwapMethodCallArguments(MethodCaller::class, 'someCall', [2, 1, 0]),
    ]);
};
