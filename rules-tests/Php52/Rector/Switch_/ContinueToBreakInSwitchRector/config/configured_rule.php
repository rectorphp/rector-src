<?php

declare(strict_types=1);

use Rector\Php52\Rector\Switch_\ContinueToBreakInSwitchRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(ContinueToBreakInSwitchRector::class);
};
