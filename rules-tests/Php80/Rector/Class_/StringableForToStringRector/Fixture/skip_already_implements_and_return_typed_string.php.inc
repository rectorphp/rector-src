<?php

namespace Rector\Tests\Php80\Rector\Class_\StringableForToStringRector\Fixture;

use Stringable;

class SkipAlreadyImplementsAndReturnTypedString implements Stringable
{
    public function __toString(): string
    {
        return 'I can stringz';
    }
}
