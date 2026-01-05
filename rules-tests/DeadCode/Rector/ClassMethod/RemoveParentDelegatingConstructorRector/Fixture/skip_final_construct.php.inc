<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveParentDelegatingConstructorRector\Fixture;

use PhpParser\Node\Scalar\String_;

final class SkipFinalConstruct extends String_
{
    final public function __construct($value, $attributes)
    {
        parent::__construct($value, $attributes);
    }
}
