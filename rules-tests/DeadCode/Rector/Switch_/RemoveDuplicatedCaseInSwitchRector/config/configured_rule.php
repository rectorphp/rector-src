<?php

declare(strict_types=1);

use Rector\DeadCode\Rector\Switch_\RemoveDuplicatedCaseInSwitchRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(RemoveDuplicatedCaseInSwitchRector::class);
};
