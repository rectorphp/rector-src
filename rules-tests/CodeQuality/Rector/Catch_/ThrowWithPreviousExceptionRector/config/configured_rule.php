<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Catch_\ThrowWithPreviousExceptionRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(ThrowWithPreviousExceptionRector::class);
};
