<?php

declare(strict_types=1);

use Rector\Strict\Rector\BooleanNot\BooleanInBooleanNotRuleFixerRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(BooleanInBooleanNotRuleFixerRector::class);
};
