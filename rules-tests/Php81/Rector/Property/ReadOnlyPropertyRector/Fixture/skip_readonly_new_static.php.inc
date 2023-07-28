<?php

namespace Rector\Tests\Php81\Rector\Property\ReadOnlyPropertyRector\Fixture;

class SkipReadonlyNewStatic
{
    private string $method;
    
    public static function create(): static
    {
        $self = new static();
        $self->method = 'hi';
        $self->method = 'hello';
        return $self;
    }
}

?>
