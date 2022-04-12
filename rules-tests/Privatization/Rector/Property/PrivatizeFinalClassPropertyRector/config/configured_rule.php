<?php

declare(strict_types=1);

use Rector\Privatization\Rector\Property\PrivatizeFinalClassPropertyRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(PrivatizeFinalClassPropertyRector::class);
};
