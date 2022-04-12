<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\If_\SimplifyIfIssetToNullCoalescingRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(SimplifyIfIssetToNullCoalescingRector::class);
};
