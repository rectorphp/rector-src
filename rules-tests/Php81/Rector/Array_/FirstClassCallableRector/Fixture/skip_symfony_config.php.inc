<?php

namespace Rector\Tests\Php81\Rector\Array_\FirstClassCallableRector\Fixture;

use Rector\Tests\Php81\Rector\Array_\FirstClassCallableRector\Source\SomeExternalObject;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container): void {
    $container->services()
        ->factory([SomeExternalObject::class, 'sleepStatic']);
};
