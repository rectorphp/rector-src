<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Core\Tests\Issues\ReturnArrayNodeAfterNopStmt\Source\ArrayAddNextNopRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(ArrayAddNextNopRector::class);
};
