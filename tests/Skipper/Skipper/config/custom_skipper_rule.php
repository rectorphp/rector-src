<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\Property\RemoveUnusedPrivatePropertyRector;
use Rector\Skipper\Skipper\Custom\ReflectionClassSkipperInterface;
use Rector\Tests\Skipper\Skipper\Fixture\CustomSkipper\SomeAttribute;

return RectorConfig::configure()
    ->withRules([RemoveUnusedPrivatePropertyRector::class])
    ->withSkip([
        RemoveUnusedPrivatePropertyRector::class => [
            new class() implements ReflectionClassSkipperInterface {
                public function skip(ReflectionClass $reflectionClass): bool
                {
                    return (bool) $reflectionClass->getAttributes(SomeAttribute::class);
                }
            },
        ],
    ]);
