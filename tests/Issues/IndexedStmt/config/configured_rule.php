<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Tests\Issues\IndexedStmt\Source\ChangeLastIndex1Rector;
use Rector\Tests\Issues\IndexedStmt\Source\RemoveIndex1Rector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rules([
        RemoveIndex1Rector::class,
        ChangeLastIndex1Rector::class,
    ]);
};
