<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\Property\AddFalseDefaultToBoolPropertyRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(AddFalseDefaultToBoolPropertyRector::class);
};
