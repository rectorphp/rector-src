<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php70\Rector\FuncCall\RenameMktimeWithoutArgsToTimeRector;

return RectorConfig::configure()->withRules([RenameMktimeWithoutArgsToTimeRector::class]);
