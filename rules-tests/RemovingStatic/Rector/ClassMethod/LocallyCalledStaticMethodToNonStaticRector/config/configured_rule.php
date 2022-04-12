<?php

declare(strict_types=1);

use Rector\RemovingStatic\Rector\ClassMethod\LocallyCalledStaticMethodToNonStaticRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(LocallyCalledStaticMethodToNonStaticRector::class);
};
