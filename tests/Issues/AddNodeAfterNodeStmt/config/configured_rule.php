<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Core\Tests\Issues\AddNodeAfterNodeStmt\Source\AddNextStmtRector;

return static function (RectorConfig $rectorConfig) {
    $rectorConfig->rule(AddNextStmtRector::class);
};
