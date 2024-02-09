<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Strict\Rector\Ternary\BooleanInTernaryOperatorRuleFixerRector;

return RectorConfig::configure()->withRules([BooleanInTernaryOperatorRuleFixerRector::class]);
