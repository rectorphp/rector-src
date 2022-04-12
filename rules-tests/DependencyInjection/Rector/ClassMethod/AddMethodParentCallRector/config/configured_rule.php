<?php

declare(strict_types=1);

use Rector\DependencyInjection\Rector\ClassMethod\AddMethodParentCallRector;
use Rector\Tests\DependencyInjection\Rector\ClassMethod\AddMethodParentCallRector\Source\ParentClassWithNewConstructor;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(AddMethodParentCallRector::class)
        ->configure([
            ParentClassWithNewConstructor::class => '__construct',
        ]);
};
