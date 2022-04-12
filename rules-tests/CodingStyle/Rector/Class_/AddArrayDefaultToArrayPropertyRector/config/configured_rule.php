<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\Class_\AddArrayDefaultToArrayPropertyRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(AddArrayDefaultToArrayPropertyRector::class);
};
