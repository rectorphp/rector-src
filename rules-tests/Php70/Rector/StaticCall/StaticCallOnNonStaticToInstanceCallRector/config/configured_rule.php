<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php70\Rector\StaticCall\StaticCallOnNonStaticToInstanceCallRector;

return RectorConfig::configure()->withRules([StaticCallOnNonStaticToInstanceCallRector::class]);
