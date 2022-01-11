<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\Stmt\NewlineAfterStatementRector;
use Rector\Core\Configuration\Option;
use Rector\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();
    $services = $containerConfigurator->services();
    $services->set(ClassPropertyAssignToConstructorPromotionRector::class);
    $services->set(NewlineAfterStatementRector::class);

    $parameters->set(Option::AUTO_IMPORT_NAMES, true);
};
