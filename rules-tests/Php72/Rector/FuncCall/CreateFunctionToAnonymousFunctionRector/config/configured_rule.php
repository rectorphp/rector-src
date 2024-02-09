<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php72\Rector\FuncCall\CreateFunctionToAnonymousFunctionRector;

return RectorConfig::configure()->withRules([CreateFunctionToAnonymousFunctionRector::class]);
