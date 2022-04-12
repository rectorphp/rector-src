<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\For_\ForRepeatedCountToOwnVariableRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(ForRepeatedCountToOwnVariableRector::class);
};
