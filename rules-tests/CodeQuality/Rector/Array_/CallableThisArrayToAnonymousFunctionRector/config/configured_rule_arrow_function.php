<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Array_\CallableThisArrayToAnonymousFunctionRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(CallableThisArrayToAnonymousFunctionRector::class, [
        CallableThisArrayToAnonymousFunctionRector::ARROW_FUNCTION => true,
    ]);
};
