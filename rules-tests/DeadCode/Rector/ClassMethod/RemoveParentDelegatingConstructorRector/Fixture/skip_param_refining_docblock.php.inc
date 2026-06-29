<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveParentDelegatingConstructorRector\Fixture;

class SomeScalarType
{
    public function __construct(array $config)
    {
    }
}

/**
 * @phpstan-type CustomScalarConfig array{name?: string, serialize?: callable}
 */
class SomeRefiningScalarType extends SomeScalarType
{
    /**
     * @param array<string, mixed> $config
     *
     * @phpstan-param CustomScalarConfig $config
     */
    public function __construct(array $config)
    {
        parent::__construct($config);
    }
}
