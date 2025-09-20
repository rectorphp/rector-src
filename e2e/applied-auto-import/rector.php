<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\Name\RenameClassRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__ . '/src',
    ]);

    $rectorConfig->ruleWithConfiguration(RenameClassRector::class, [
        'DateTime' => 'DateTimeInterface'
    ]);

    $rectorConfig->importNames();
    $rectorConfig->removeUnusedImports();
};
