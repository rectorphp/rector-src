<?php

declare(strict_types=1);

use Rector\Tests\Transform\Rector\New_\NewArgToMethodCallRector\Source\SomeDotenv;
use Rector\Transform\Rector\New_\NewArgToMethodCallRector;
use Rector\Transform\ValueObject\NewArgToMethodCall;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(NewArgToMethodCallRector::class)
        ->configure([new NewArgToMethodCall(SomeDotenv::class, true, 'usePutenv')]);
};
