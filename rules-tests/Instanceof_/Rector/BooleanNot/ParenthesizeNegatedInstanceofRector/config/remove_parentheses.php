<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Instanceof_\Rector\BooleanNot\ParenthesizeNegatedInstanceofRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(ParenthesizeNegatedInstanceofRector::class, [
        'mode' => ParenthesizeNegatedInstanceofRector::REMOVE_PARENTHESES,
    ]);
};
