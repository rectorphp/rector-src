<?php

declare(strict_types=1);

use Rector\DeadCode\Rector\StaticCall\RemoveParentCallWithoutParentRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(RemoveParentCallWithoutParentRector::class);
};
