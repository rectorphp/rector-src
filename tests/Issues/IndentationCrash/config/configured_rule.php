<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\For_\ForRepeatedCountToOwnVariableRector;
use Rector\Config\RectorConfig;
use Rector\Php71\Rector\FuncCall\CountOnNullRector;
use Rector\Php81\Rector\FuncCall\NullToStrictStringFuncCallArgRector;
use Rector\Renaming\Rector\FuncCall\RenameFunctionRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig
        ->ruleWithConfiguration(RenameFunctionRector::class, [
            'sizeof' => 'count',
        ]);
    $rectorConfig->rule(ForRepeatedCountToOwnVariableRector::class);
    $rectorConfig->rule(CountOnNullRector::class);
    $rectorConfig->rule(NullToStrictStringFuncCallArgRector::class);
};
