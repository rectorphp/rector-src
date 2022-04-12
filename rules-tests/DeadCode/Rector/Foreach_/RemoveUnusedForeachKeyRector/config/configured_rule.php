<?php

declare(strict_types=1);

use Rector\DeadCode\Rector\Foreach_\RemoveUnusedForeachKeyRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(RemoveUnusedForeachKeyRector::class);
};
