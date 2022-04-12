<?php

declare(strict_types=1);

use Rector\Php54\Rector\Break_\RemoveZeroBreakContinueRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(RemoveZeroBreakContinueRector::class);
};
