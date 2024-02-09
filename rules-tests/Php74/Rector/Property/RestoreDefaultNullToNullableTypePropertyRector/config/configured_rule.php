<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php74\Rector\Property\RestoreDefaultNullToNullableTypePropertyRector;

return RectorConfig::configure()->withRules([RestoreDefaultNullToNullableTypePropertyRector::class]);
