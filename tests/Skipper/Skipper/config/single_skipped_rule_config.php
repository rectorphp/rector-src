<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Class_\InlineConstructorDefaultToPropertyRector;
use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\ClassMethod\RemoveUnusedPromotedPropertyRector;

return RectorConfig::configure()->withSkip([InlineConstructorDefaultToPropertyRector::class])->withRules(
    [InlineConstructorDefaultToPropertyRector::class, RemoveUnusedPromotedPropertyRector::class]
);
