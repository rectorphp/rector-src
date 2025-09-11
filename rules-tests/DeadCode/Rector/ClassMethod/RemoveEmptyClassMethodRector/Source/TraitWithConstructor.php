<?php

declare(strict_types=1);

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveEmptyClassMethodRector\Source;

trait TraitWithConstructor
{
    public function __construct()
    {
        return;
    }
}
