<?php

declare(strict_types=1);

use Rector\Php80\Rector\Ternary\GetDebugTypeRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(GetDebugTypeRector::class);
};
