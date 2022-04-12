<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Assign\SplitListAssignToSeparateLineRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(SplitListAssignToSeparateLineRector::class);
};
