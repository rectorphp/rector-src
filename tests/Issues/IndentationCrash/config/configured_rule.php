<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\For_\ForRepeatedCountToOwnVariableRector;
use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\FuncCall\RenameFunctionRector;
use Rector\Set\ValueObject\LevelSetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig
        ->ruleWithConfiguration(RenameFunctionRector::class, [
                'sizeof' => 'count',
            ]);
    $rectorConfig->rule(ForRepeatedCountToOwnVariableRector::class);

    $rectorConfig->sets([LevelSetList::UP_TO_PHP_81]);
};
