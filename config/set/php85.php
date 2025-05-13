<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Tests\Php85\Rector\ArrayDimFetch\ArrayFirstLastRector\ArrayFirstLastRectorTest;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rules(
        [
            ArrayFirstLastRectorTest::class,
        ]
    );
};
