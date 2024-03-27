<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedConstructorParamRector\Source;

interface InterfaceWithConstruct
{
    public function __construct(string $name, array $config);
}
