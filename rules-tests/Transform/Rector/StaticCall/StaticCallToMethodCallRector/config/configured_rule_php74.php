<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Transform\Rector\StaticCall\StaticCallToMethodCallRector;
use Rector\Transform\ValueObject\StaticCallToMethodCall;
use Rector\ValueObject\PhpVersionFeature;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->phpVersion(PhpVersionFeature::TYPED_PROPERTIES);

    $rectorConfig
        ->ruleWithConfiguration(StaticCallToMethodCallRector::class, [
            new StaticCallToMethodCall(
                'Illuminate\Support\Facades\App',
                '*',
                'Illuminate\Foundation\Application',
                '*'
            ),
        ]);
};
