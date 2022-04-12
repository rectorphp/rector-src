<?php

declare(strict_types=1);

use Rector\Removing\Rector\FuncCall\RemoveFuncCallArgRector;
use Rector\Removing\ValueObject\RemoveFuncCallArg;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(RemoveFuncCallArgRector::class)
        ->configure([new RemoveFuncCallArg('ldap_first_attribute', 2)]);
};
