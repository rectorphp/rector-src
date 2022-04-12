<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\FuncCall\RemoveSoleValueSprintfRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(RemoveSoleValueSprintfRector::class);
};
