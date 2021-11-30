<?php

declare(strict_types=1);

use Rector\Php52\Rector\Property\VarToPublicPropertyRector;
use Rector\Php52\Rector\Switch_\ContinueToBreakInSwitchRector;
use Rector\Removing\Rector\FuncCall\RemoveFuncCallArgRector;
use Rector\Removing\ValueObject\RemoveFuncCallArg;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(VarToPublicPropertyRector::class);
    $services->set(ContinueToBreakInSwitchRector::class);

    $services->set(RemoveFuncCallArgRector::class)
        ->configure([
            // see https://www.php.net/manual/en/function.ldap-first-attribute.php
            new RemoveFuncCallArg('ldap_first_attribute', 2),
        ]);
};
