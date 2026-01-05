<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveParentDelegatingConstructorRector\Fixture;

use PhpParser\Node\Scalar\String_;

final class SkipPrivateConstruct extends String_
{
    private function __construct($value, $attributes)
    {
        parent::__construct($value, $attributes);
    }

    public static function create($value, $attributes)
    {
        return new self($value, $attributes);
    }
}
