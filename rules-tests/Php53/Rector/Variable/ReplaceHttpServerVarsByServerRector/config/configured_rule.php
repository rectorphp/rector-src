<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php53\Rector\Variable\ReplaceHttpServerVarsByServerRector;

return RectorConfig::configure()->withRules([ReplaceHttpServerVarsByServerRector::class]);
