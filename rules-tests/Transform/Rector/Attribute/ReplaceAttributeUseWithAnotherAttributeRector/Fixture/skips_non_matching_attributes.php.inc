<?php

namespace Rector\Tests\Transform\Rector\Attribute\ReplaceAttributeUseWithAnotherAttributeRector\Fixture;

class SomeClass
{
    #[\Something\FooBar()]
    public string $name;
}

?>
