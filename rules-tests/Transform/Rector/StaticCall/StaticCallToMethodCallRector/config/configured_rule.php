<?php

declare(strict_types=1);

use Rector\Transform\Rector\StaticCall\StaticCallToMethodCallRector;
use Rector\Transform\ValueObject\StaticCallToMethodCall;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
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
