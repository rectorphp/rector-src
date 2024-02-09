<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php70\Rector\Ternary\TernaryToSpaceshipRector;

return RectorConfig::configure()->withRules([TernaryToSpaceshipRector::class]);
