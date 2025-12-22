<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveParentDelegatingConstructorRector\Fixture;

use PhpParser\Node\Scalar\String_;

final class SkipFirstClassCallable extends String_
{
    public function __construct($value, $attributes)
    {
        parent::__construct(...);
    }
}
