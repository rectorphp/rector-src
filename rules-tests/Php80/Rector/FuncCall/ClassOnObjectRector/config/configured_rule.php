<?php

declare(strict_types=1);

use Rector\Php80\Rector\FuncCall\ClassOnObjectRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(ClassOnObjectRector::class);
};
