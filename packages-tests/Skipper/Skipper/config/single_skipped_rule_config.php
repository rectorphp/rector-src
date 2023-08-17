<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Class_\InlineConstructorDefaultToPropertyRector;
use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\ClassMethod\RemoveUnusedPromotedPropertyRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rules([
        InlineConstructorDefaultToPropertyRector::class,
        RemoveUnusedPromotedPropertyRector::class,
    ]);

    $rectorConfig->skip([InlineConstructorDefaultToPropertyRector::class]);
};
