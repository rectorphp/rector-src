<?php

declare(strict_types=1);

use Rector\Strict\Rector\Ternary\BooleanInTernaryOperatorRuleFixerRector;

return static function (\Rector\Config\RectorConfig $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(BooleanInTernaryOperatorRuleFixerRector::class);
};
