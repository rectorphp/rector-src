<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\Stmt\RemoveUselessAliasInUseStatementRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()->withRules([RemoveUselessAliasInUseStatementRector::class]);
