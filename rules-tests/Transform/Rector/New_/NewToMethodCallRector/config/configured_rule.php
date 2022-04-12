<?php

declare(strict_types=1);

use Rector\Tests\Transform\Rector\New_\NewToMethodCallRector\Source\MyClass;
use Rector\Tests\Transform\Rector\New_\NewToMethodCallRector\Source\MyClassFactory;
use Rector\Transform\Rector\New_\NewToMethodCallRector;
use Rector\Transform\ValueObject\NewToMethodCall;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(NewToMethodCallRector::class)
        ->configure([new NewToMethodCall(MyClass::class, MyClassFactory::class, 'create')]);
};
