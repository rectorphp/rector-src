<?php

declare(strict_types=1);

use Rector\Restoration\Rector\ClassConstFetch\MissingClassConstantReferenceToStringRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(MissingClassConstantReferenceToStringRector::class);
};
