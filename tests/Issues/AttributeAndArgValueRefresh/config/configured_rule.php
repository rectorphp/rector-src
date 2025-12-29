<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php81\Rector\Array_\ArrayToFirstClassCallableRector;
use Rector\Tests\Issues\AttributeAndArgValueRefresh\Source\SetArrayArgValueRector;
use Rector\Tests\Issues\AttributeAndArgValueRefresh\Source\SetArrayAttributeValueRector;

return RectorConfig::configure()
    ->withRules([
        SetArrayArgValueRector::class,
        SetArrayAttributeValueRector::class,
        ArrayToFirstClassCallableRector::class,
    ]);
