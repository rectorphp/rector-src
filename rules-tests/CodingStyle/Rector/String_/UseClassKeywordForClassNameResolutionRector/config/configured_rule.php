<?php

declare(strict_types=1);

use Rector\CodingStyle\Rector\String_\UseClassKeywordForClassNameResolutionRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()->withRules([UseClassKeywordForClassNameResolutionRector::class]);
