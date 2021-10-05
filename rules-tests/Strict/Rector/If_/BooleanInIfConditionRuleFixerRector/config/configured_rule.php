<?php

declare(strict_types=1);

use Rector\Strict\Rector\If_\BooleanInIfConditionRuleFixerRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(BooleanInIfConditionRuleFixerRector::class);
};
