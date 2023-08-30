<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Core\Tests\Issues\RemoveClosureUseByIndex\Source\ChangeClosureUseByIndexRector;
use Rector\Core\Tests\Issues\RemoveClosureUseByIndex\Source\RemoveClosureUseByIndexRector;

return static function (RectorConfig $rectorConfig): void {
    /**
     * index a, b, c
     * - start with remove index 1 with key "b"
     * - change index 1 to "d"
     * - result: a, d
     */
    $rectorConfig->rules([
        RemoveClosureUseByIndexRector::class,
        ChangeClosureUseByIndexRector::class,
    ]);
};
