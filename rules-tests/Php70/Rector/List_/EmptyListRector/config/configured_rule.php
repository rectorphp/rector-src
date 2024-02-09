<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php70\Rector\List_\EmptyListRector;

return RectorConfig::configure()->withRules([EmptyListRector::class]);
