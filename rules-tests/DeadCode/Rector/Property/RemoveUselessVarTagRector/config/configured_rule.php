<?php

declare(strict_types=1);

use Rector\DeadCode\Rector\Property\RemoveUselessVarTagRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(RemoveUselessVarTagRector::class);
};
