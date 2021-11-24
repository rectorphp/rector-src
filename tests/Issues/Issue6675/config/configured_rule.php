<?php

declare(strict_types=1);

use Rector\DeadCode\Rector\Assign\RemoveAssignOfVoidReturnFunctionRector;
use Rector\Set\ValueObject\SetList;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(SetList::MYSQL_TO_MYSQLI);

    $services = $containerConfigurator->services();
    $services->set(RemoveAssignOfVoidReturnFunctionRector::class);
};
