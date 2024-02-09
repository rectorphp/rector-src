<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php70\Rector\MethodCall\ThisCallOnStaticMethodToStaticCallRector;

return RectorConfig::configure()->withRules([ThisCallOnStaticMethodToStaticCallRector::class]);
