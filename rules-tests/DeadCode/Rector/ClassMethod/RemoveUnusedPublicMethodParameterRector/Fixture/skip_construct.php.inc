<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedPublicMethodParameterRector\Fixture;

final class SkipConstruct
{
    public function __construct(private readonly string $a, private readonly string $b)
    {
    }
}