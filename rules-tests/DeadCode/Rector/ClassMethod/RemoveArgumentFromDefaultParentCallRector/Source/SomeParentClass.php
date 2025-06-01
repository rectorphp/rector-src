<?php

declare(strict_types=1);

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveArgumentFromDefaultParentCallRector\Source;

class SomeParentClass
{
    public function __construct(array $params = [])
    {
    }
}