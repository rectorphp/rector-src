<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Tests\Transform\Rector\StaticCall\StaticCallToMethodCallRector\Source\TargetFileSystem;
use Rector\Transform\Rector\StaticCall\StaticCallToMethodCallRector;
use Rector\Transform\ValueObject\StaticCallToMethodCall;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig
        ->ruleWithConfiguration(StaticCallToMethodCallRector::class, [
            new StaticCallToMethodCall('Nette\Utils\FileSystem', 'write', TargetFileSystem::class, 'dumpFile'),
            new StaticCallToMethodCall(
                'Illuminate\Support\Facades\Response',
                '*',
                'Illuminate\Contracts\Routing\ResponseFactory',
                '*'
            ),
        ]);
};
