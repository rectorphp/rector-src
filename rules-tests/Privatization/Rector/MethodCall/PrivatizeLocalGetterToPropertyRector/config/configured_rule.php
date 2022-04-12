<?php

declare(strict_types=1);

use Rector\Privatization\Rector\MethodCall\PrivatizeLocalGetterToPropertyRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(PrivatizeLocalGetterToPropertyRector::class);
};
