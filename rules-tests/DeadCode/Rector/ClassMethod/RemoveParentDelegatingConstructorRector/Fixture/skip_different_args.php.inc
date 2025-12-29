<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveParentDelegatingConstructorRector\Fixture;

use PhpParser\Node\Scalar\String_;

final class SomeDifferentArgs extends String_
{
    public function __construct($value, $attributes)
    {
        parent::__construct($value, []);
    }
}
