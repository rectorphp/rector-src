<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Core\Tests\Issues\AddNodeBeforeNodeStmt\Source\AddBeforeStmtRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(AddBeforeStmtRector::class);
};
