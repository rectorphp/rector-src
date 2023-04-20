<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php56\Rector\FunctionLike\AddDefaultValueForUndefinedVariableRector;
use Rector\Php70\Rector\Ternary\TernaryToNullCoalescingRector;
return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rules([
    	TernaryToNullCoalescingRector::class,
        AddDefaultValueForUndefinedVariableRector::class,
    ]);
};
