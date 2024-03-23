<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\ClassConstFetch\RenameClassConstFetchRector;
use Rector\Renaming\ValueObject\RenameClassAndConstFetch;
use Rector\Renaming\ValueObject\RenameClassConstFetch;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig
        ->ruleWithConfiguration(RenameClassConstFetchRector::class, [
            new RenameClassAndConstFetch(
                'OldClass1',
                'NOTICE',
                'NewClass1',
                'NOTICE'
            ),
        ]);
};