<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Core\Tests\Issues\ChangeToDifferentStmt\Source\TestRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(TestRector::class);
};
