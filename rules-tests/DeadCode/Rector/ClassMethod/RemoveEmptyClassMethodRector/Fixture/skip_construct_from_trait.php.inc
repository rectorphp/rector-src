<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveEmptyClassMethodRector\Fixture;

use Rector\Tests\DeadCode\Rector\ClassMethod\RemoveEmptyClassMethodRector\Source\TraitWithConstructor;

class SkipConstructFromTrait
{
    use TraitWithConstructor;

    public function __construct()
    {
    }
}
