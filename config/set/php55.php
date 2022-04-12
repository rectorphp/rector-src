<?php

declare(strict_types=1);

use Rector\Php55\Rector\Class_\ClassConstantToSelfClassRector;
use Rector\Php55\Rector\FuncCall\GetCalledClassToSelfClassRector;
use Rector\Php55\Rector\FuncCall\GetCalledClassToStaticClassRector;
use Rector\Php55\Rector\FuncCall\PregReplaceEModifierRector;
use Rector\Php55\Rector\String_\StringClassNameToClassConstantRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(StringClassNameToClassConstantRector::class);
    $services->set(ClassConstantToSelfClassRector::class);
    $services->set(PregReplaceEModifierRector::class);
    $services->set(GetCalledClassToSelfClassRector::class);
    $services->set(GetCalledClassToStaticClassRector::class);
};
