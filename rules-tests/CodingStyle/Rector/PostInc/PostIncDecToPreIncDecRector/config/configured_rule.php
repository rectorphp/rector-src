<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\PostInc\PostIncDecToPreIncDecRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()->withRules([PostIncDecToPreIncDecRector::class]);
