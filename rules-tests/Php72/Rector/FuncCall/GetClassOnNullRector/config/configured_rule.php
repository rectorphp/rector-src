<?php

declare(strict_types=1);

use Rector\Php72\Rector\FuncCall\GetClassOnNullRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(GetClassOnNullRector::class);
};
