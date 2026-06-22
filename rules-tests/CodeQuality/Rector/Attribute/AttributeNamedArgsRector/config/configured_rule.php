<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Attribute\AttributeNamedArgsRector;
use Rector\CodeQuality\ValueObject\AttributeNamedArgs;
use Rector\Config\RectorConfig;
use Rector\Tests\CodeQuality\Rector\Attribute\AttributeNamedArgsRector\Source\Middleware;
use Rector\Tests\CodeQuality\Rector\Attribute\AttributeNamedArgsRector\Source\MiddlewareWithVariadic;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(AttributeNamedArgsRector::class, [
        new AttributeNamedArgs(Middleware::class),
        new AttributeNamedArgs(MiddlewareWithVariadic::class),
    ]);
};
