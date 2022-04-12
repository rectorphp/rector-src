<?php

declare(strict_types=1);

use Rector\DeadCode\Rector\Return_\RemoveDeadConditionAboveReturnRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(RemoveDeadConditionAboveReturnRector::class);
};
