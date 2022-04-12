<?php

declare(strict_types=1);

use Rector\Strict\Rector\If_\BooleanInIfConditionRuleFixerRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(BooleanInIfConditionRuleFixerRector::class);
};
