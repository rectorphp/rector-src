<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\Ternary\TernaryToBooleanOrFalseToBooleanAndRector;

return RectorConfig::configure()->withRules([TernaryToBooleanOrFalseToBooleanAndRector::class]);
