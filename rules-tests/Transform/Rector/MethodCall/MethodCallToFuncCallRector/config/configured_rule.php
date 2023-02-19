<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

use Rector\Transform\Rector\MethodCall\MethodCallToFuncCallRector;
use Rector\Transform\ValueObject\MethodCallToFuncCall;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(MethodCallToFuncCallRector::class, [
        new MethodCallToFuncCall(
            'Rector\Tests\Transform\Rector\MethodCall\MethodCallToFuncCallRector\Source\ParentControllerWithRender',
            'render',
            'view'
        ),
    ]);
};
