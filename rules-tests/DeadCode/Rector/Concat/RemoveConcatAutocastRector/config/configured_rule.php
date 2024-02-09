<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\Concat\RemoveConcatAutocastRector;

return RectorConfig::configure()->withRules([RemoveConcatAutocastRector::class]);
