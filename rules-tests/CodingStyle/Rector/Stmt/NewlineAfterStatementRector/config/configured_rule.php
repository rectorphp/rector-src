<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\Stmt\NewlineAfterStatementRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()->withRules([NewlineAfterStatementRector::class]);
