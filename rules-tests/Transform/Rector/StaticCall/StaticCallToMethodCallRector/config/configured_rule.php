<?php

declare(strict_types=1);

use Rector\Transform\Rector\StaticCall\StaticCallToMethodCallRector;
use Rector\Transform\ValueObject\StaticCallToMethodCall;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(StaticCallToMethodCallRector::class)
        ->configure([
            new StaticCallToMethodCall(
                'Nette\Utils\FileSystem',
                'write',
                'Symplify\SmartFileSystem\SmartFileSystem',
                'dumpFile'
            ),
            new StaticCallToMethodCall(
                'Illuminate\Support\Facades\Response',
                '*',
                'Illuminate\Contracts\Routing\ResponseFactory',
                '*'
            ),
        ]);
};
