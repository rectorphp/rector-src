<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php73\Rector\FuncCall\RegexDashEscapeRector;

return RectorConfig::configure()->withRules([RegexDashEscapeRector::class]);
