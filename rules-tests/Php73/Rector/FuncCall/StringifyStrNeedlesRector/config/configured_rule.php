<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php73\Rector\FuncCall\StringifyStrNeedlesRector;

return RectorConfig::configure()->withRules([StringifyStrNeedlesRector::class]);
