<?php

declare(strict_types=1);

use Rector\Naming\Rector\Foreach_\RenameForeachValueVariableToMatchExprVariableRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(RenameForeachValueVariableToMatchExprVariableRector::class);
};
