<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php71\Rector\TryCatch\MultiExceptionCatchRector;

return RectorConfig::configure()->withRules([MultiExceptionCatchRector::class]);
