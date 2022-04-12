<?php

declare(strict_types=1);

use Rector\Php71\Rector\Assign\AssignArrayToStringRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(AssignArrayToStringRector::class);
};
