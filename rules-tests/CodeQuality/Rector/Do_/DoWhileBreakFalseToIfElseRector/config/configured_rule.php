<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Do_\DoWhileBreakFalseToIfElseRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(DoWhileBreakFalseToIfElseRector::class);
};
