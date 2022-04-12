<?php

declare(strict_types=1);

use Rector\Php52\Rector\Property\VarToPublicPropertyRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(VarToPublicPropertyRector::class);
};
