<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php81\Rector\Property\ReadOnlyPropertyRector;

return RectorConfig::configure()->withRules([ReadOnlyPropertyRector::class]);
