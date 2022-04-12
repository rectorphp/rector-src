<?php

declare(strict_types=1);

use Rector\Tests\Transform\Rector\StaticCall\StaticCallToFuncCallRector\Source\SomeOldStaticClass;
use Rector\Transform\Rector\StaticCall\StaticCallToFuncCallRector;
use Rector\Transform\ValueObject\StaticCallToFuncCall;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(StaticCallToFuncCallRector::class)
        ->configure([new StaticCallToFuncCall(SomeOldStaticClass::class, 'render', 'view')]);
};
