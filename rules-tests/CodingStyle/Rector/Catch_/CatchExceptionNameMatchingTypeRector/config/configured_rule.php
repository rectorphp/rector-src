<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\Catch_\CatchExceptionNameMatchingTypeRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()->withRules([CatchExceptionNameMatchingTypeRector::class]);
