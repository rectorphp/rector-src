<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php70\Rector\FunctionLike\ExceptionHandlerTypehintRector;

return RectorConfig::configure()->withRules([ExceptionHandlerTypehintRector::class]);
