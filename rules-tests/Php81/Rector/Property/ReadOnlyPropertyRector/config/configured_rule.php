<?php

declare(strict_types=1);

use Rector\Php81\Rector\Property\ReadOnlyPropertyRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(ReadOnlyPropertyRector::class);
};
