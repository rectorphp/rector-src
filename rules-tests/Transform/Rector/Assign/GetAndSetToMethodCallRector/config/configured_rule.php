<?php

declare(strict_types=1);

use Rector\Tests\Transform\Rector\Assign\GetAndSetToMethodCallRector\Source\Klarka;
use Rector\Tests\Transform\Rector\Assign\GetAndSetToMethodCallRector\Source\SomeContainer;
use Rector\Transform\Rector\Assign\GetAndSetToMethodCallRector;
use Rector\Transform\ValueObject\GetAndSetToMethodCall;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(GetAndSetToMethodCallRector::class)
        ->configure([
            new GetAndSetToMethodCall(SomeContainer::class, 'getService', 'addService'),
            new GetAndSetToMethodCall(Klarka::class, 'get', 'set'),
        ]);
};
