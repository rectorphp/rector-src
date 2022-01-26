<?php

declare(strict_types=1);

use Rector\Php81\Rector\Class_\MyCLabsClassToEnumRector;
use Rector\Php81\Rector\Class_\SpatieEnumClassToEnumRector;
use Rector\Php81\Rector\ClassConst\FinalizePublicClassConstantRector;
use Rector\Php81\Rector\ClassMethod\NewInInitializerRector;
use Rector\Php81\Rector\FuncCall\NullToStrictStringFuncCallArgRector;
use Rector\Php81\Rector\FuncCall\Php81ResourceReturnToObjectRector;
use Rector\Php81\Rector\FunctionLike\IntersectionTypesRector;
use Rector\Php81\Rector\MethodCall\MyCLabsMethodCallToEnumConstRector;
use Rector\Php81\Rector\Property\ReadOnlyPropertyRector;
use Rector\TypeDeclaration\Rector\ClassMethod\ReturnNeverTypeRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(ReturnNeverTypeRector::class);
    $services->set(MyCLabsClassToEnumRector::class);
    $services->set(MyCLabsMethodCallToEnumConstRector::class);
    $services->set(FinalizePublicClassConstantRector::class);
    $services->set(ReadOnlyPropertyRector::class);
    $services->set(SpatieEnumClassToEnumRector::class);
    $services->set(Php81ResourceReturnToObjectRector::class);
    $services->set(NewInInitializerRector::class);
    $services->set(IntersectionTypesRector::class);
    $services->set(NullToStrictStringFuncCallArgRector::class);
};
