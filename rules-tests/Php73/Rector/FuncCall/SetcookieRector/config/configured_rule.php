<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php73\Rector\FuncCall\SetCookieRector;

return RectorConfig::configure()->withRules([SetCookieRector::class]);
