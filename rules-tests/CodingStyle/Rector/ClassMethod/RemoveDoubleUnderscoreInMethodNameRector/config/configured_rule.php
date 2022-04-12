<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\ClassMethod\RemoveDoubleUnderscoreInMethodNameRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(RemoveDoubleUnderscoreInMethodNameRector::class);
};
