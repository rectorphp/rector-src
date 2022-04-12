<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\Encapsed\WrapEncapsedVariableInCurlyBracesRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(WrapEncapsedVariableInCurlyBracesRector::class);
};
