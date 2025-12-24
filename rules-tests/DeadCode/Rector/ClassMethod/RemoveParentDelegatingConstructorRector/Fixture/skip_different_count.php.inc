<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveParentDelegatingConstructorRector\Fixture;

use PhpParser\Node\Scalar\String_;

final class SkipDifferentCount extends String_
{
    public function __construct($value)
    {
        parent::__construct($value, []);
    }
}
