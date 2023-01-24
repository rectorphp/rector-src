<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Core\Tests\Issues\AddNodeAfterNodeStmt\Source\AddNextNopRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(AddNextNopRector::class);
};
