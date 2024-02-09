<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Naming\Rector\Foreach_\RenameForeachValueVariableToMatchExprVariableRector;

return RectorConfig::configure()->withRules([RenameForeachValueVariableToMatchExprVariableRector::class]);
