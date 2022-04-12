<?php

declare(strict_types=1);

use Rector\Transform\Rector\FuncCall\FuncCallToStaticCallRector;
use Rector\Transform\ValueObject\FuncCallToStaticCall;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(FuncCallToStaticCallRector::class)
        ->configure([
            new FuncCallToStaticCall('view', 'SomeStaticClass', 'render'),
            new FuncCallToStaticCall('SomeNamespaced\view', 'AnotherStaticClass', 'render'),
        ]);
};
