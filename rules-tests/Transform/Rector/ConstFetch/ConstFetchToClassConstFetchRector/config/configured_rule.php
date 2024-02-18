<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Transform\Rector\ConstFetch\ConstFetchToClassConstFetchRector;
use Rector\Transform\ValueObject\ConstFetchToClassConstFetch;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig
        ->ruleWithConfiguration(ConstFetchToClassConstFetchRector::class, [
            new ConstFetchToClassConstFetch('CONTEXT_COURSE', 'core\context\course', 'LEVEL'),
        ]);
};
