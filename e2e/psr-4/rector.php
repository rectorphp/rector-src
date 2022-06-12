<?php

declare(strict_types=1);

use Rector\Set\ValueObject\SetList;

return static function (Rector\Config\RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__ . '/src',
    ]);

    $rectorConfig->sets([
        SetList::PSR_4,
    ]);
};
