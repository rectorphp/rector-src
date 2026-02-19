<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveParentDelegatingConstructorRector\Fixture;

use PhpParser\Node\Scalar\String_;
use SensitiveParameter;

final class SomeAttributeDecoratedArgs extends String_
{
    public function __construct(#[SensitiveParameter] string $value, array $attributes = [])
    {
        parent::__construct($value, $attributes);
    }
}
