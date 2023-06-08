<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Strict\Rector\BooleanNot\BooleanInBooleanNotRuleFixerRector;
use Rector\Strict\Rector\ClassMethod\AddConstructorParentCallRector;
use Rector\Strict\Rector\Empty_\DisallowedEmptyRuleFixerRector;
use Rector\Strict\Rector\If_\BooleanInIfConditionRuleFixerRector;
use Rector\Strict\Rector\Ternary\BooleanInTernaryOperatorRuleFixerRector;
use Rector\Strict\Rector\Ternary\DisallowedShortTernaryRuleFixerRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rules([
        BooleanInBooleanNotRuleFixerRector::class,
        AddConstructorParentCallRector::class,
        DisallowedEmptyRuleFixerRector::class,
        BooleanInIfConditionRuleFixerRector::class,
        BooleanInTernaryOperatorRuleFixerRector::class,
        DisallowedShortTernaryRuleFixerRector::class,
    ]);
};
