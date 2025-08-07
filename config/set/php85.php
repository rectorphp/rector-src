<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php85\Rector\ArrayDimFetch\ArrayFirstLastRector;
use Rector\Removing\Rector\FuncCall\RemoveFuncCallArgRector;
use Rector\Removing\ValueObject\RemoveFuncCallArg;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rules([ArrayFirstLastRector::class]);

    $rectorConfig->ruleWithConfiguration(
        RemoveFuncCallArgRector::class,
        [
            new RemoveFuncCallArg('openssl_pkey_derive', 2),
        ]
    );
};
