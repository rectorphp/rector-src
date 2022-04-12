<?php

declare(strict_types=1);

use Rector\EarlyReturn\Rector\Foreach_\ChangeNestedForeachIfsToEarlyContinueRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(ChangeNestedForeachIfsToEarlyContinueRector::class);
};
