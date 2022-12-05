<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\If_\SimplifyIfElseWithSameContentRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->indent("\t", 1);
    $rectorConfig->rule(SimplifyIfElseWithSameContentRector::class);
};
