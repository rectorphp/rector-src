<?php

declare(strict_types=1);

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveEmptyClassMethodRector\Source;

class MyDIContainer
{
    public function call(callable $callback, array $parameters = []): mixed
    {
    }
}