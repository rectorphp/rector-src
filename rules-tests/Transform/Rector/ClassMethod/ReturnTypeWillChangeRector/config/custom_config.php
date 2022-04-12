<?php

declare(strict_types=1);

use Rector\Transform\Rector\ClassMethod\ReturnTypeWillChangeRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(ReturnTypeWillChangeRector::class)
        ->configure([
            'ArrayAccess' => ['offsetExists'],
        ]);
};
