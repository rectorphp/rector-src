<?php

declare(strict_types=1);

use Rector\Naming\Rector\Foreach_\RenameForeachValueVariableToMatchMethodCallReturnTypeRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(RenameForeachValueVariableToMatchMethodCallReturnTypeRector::class);
};
