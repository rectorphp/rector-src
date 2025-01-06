<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Tests\Transform\Rector\FuncCall\FuncCallToMethodCallRector\Source\SomeTranslator;
use Rector\Transform\Rector\FuncCall\FuncCallToMethodCallRector;
use Rector\Transform\ValueObject\FuncCallToMethodCall;
use Rector\ValueObject\PhpVersion;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->phpVersion(PhpVersion::PHP_80);

    $rectorConfig
        ->ruleWithConfiguration(FuncCallToMethodCallRector::class, [
            new FuncCallToMethodCall('view', 'Namespaced\SomeRenderer', 'render'),

            new FuncCallToMethodCall('translate', SomeTranslator::class, 'translateMethod'),

            new FuncCallToMethodCall(
                'Rector\Tests\Transform\Rector\Function_\FuncCallToMethodCallRector\Source\some_view_function',
                'Namespaced\SomeRenderer',
                'render'
            ),
        ]);
};
