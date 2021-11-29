<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Rector\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector;
use Rector\CodingStyle\Rector\Stmt\NewlineAfterStatementRector;

return static function (ContainerConfigurator $containerConfigurator): void {

    $services = $containerConfigurator->services();
    $services->set(ClassPropertyAssignToConstructorPromotionRector::class);
    $services->set(NewlineAfterStatementRector::class);
};
