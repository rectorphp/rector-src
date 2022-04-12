<?php

declare(strict_types=1);

use Rector\Tests\Transform\Rector\New_\NewToConstructorInjectionRector\Source\DummyValidator;
use Rector\Transform\Rector\New_\NewToConstructorInjectionRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(NewToConstructorInjectionRector::class)
        ->configure([DummyValidator::class]);
};
