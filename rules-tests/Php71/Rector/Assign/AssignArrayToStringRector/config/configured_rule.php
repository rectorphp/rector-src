<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php71\Rector\Assign\AssignArrayToStringRector;

return RectorConfig::configure()->withRules([AssignArrayToStringRector::class]);
