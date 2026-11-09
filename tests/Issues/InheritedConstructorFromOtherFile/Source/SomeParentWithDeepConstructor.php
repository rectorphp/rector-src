<?php

declare(strict_types=1);

namespace Rector\Tests\Issues\InheritedConstructorFromOtherFile\Source;

abstract class SomeParentWithDeepConstructor
{
    /**
     * Pushes the constructor further into this file than the child fixture file is long
     */
    public function padding(): array
    {
        return [
            'padding padding padding padding padding padding padding padding padding',
            'padding padding padding padding padding padding padding padding padding',
            'padding padding padding padding padding padding padding padding padding',
            'padding padding padding padding padding padding padding padding padding',
            'padding padding padding padding padding padding padding padding padding',
            'padding padding padding padding padding padding padding padding padding',
            'padding padding padding padding padding padding padding padding padding',
            'padding padding padding padding padding padding padding padding padding',
            'padding padding padding padding padding padding padding padding padding',
            'padding padding padding padding padding padding padding padding padding',
            'padding padding padding padding padding padding padding padding padding',
            'padding padding padding padding padding padding padding padding padding',
            'padding padding padding padding padding padding padding padding padding',
            'padding padding padding padding padding padding padding padding padding',
            'padding padding padding padding padding padding padding padding padding',
            'padding padding padding padding padding padding padding padding padding',
            'padding padding padding padding padding padding padding padding padding',
            'padding padding padding padding padding padding padding padding padding',
            'padding padding padding padding padding padding padding padding padding',
            'padding padding padding padding padding padding padding padding padding',
            'padding padding padding padding padding padding padding padding padding',
            'padding padding padding padding padding padding padding padding padding',
            'padding padding padding padding padding padding padding padding padding',
            'padding padding padding padding padding padding padding padding padding',
            'padding padding padding padding padding padding padding padding padding',
        ];
    }

    public function __construct(protected string $name)
    {
    }
}
