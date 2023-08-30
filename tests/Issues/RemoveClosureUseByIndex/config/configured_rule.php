<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Core\Tests\Issues\RemoveClosureUseByIndex\Source\ChangeClosureUseByIndexRector;
use Rector\Core\Tests\Issues\RemoveClosureUseByIndex\Source\RemoveClosureUseByIndexRector;

return static function (RectorConfig $rectorConfig): void {
    /**
     * index 0, 1, 2 with values a, b, c
     * - start with remove index 1 with value "b"
     * - change index 1 value "d"
     * - result: a, d
     */
    $rectorConfig->rules([
        RemoveClosureUseByIndexRector::class,
        ChangeClosureUseByIndexRector::class,
    ]);
};
