<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(\Rector\DeadCode\Rector\If_\RemoveDeadInstanceOfRector::class);

    $services->set(\Rector\Strict\Rector\Ternary\BooleanInTernaryOperatorRuleFixerRector::class)
        ->configure([
            \Rector\Strict\Rector\Ternary\BooleanInTernaryOperatorRuleFixerRector::TREAT_AS_NON_EMPTY => false,
        ]);

    $services->set(\Rector\Strict\Rector\Empty_\DisallowedEmptyRuleFixerRector::class)
        ->configure([
            \Rector\Strict\Rector\Empty_\DisallowedEmptyRuleFixerRector::TREAT_AS_NON_EMPTY => false,
        ]);
};
