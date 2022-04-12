<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Foreach_\SimplifyForeachToCoalescingRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(SimplifyForeachToCoalescingRector::class);
};
