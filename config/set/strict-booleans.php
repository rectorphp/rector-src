<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Strict\Rector\BooleanNot\BooleanInBooleanNotRuleFixerRector;
use Rector\Strict\Rector\Empty_\DisallowedEmptyRuleFixerRector;
use Rector\Strict\Rector\Ternary\DisallowedShortTernaryRuleFixerRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rules([
        BooleanInBooleanNotRuleFixerRector::class,
        DisallowedEmptyRuleFixerRector::class,
        DisallowedShortTernaryRuleFixerRector::class,
    ]);
};
