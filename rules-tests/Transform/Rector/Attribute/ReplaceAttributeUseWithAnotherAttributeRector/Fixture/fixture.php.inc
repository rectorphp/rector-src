<?php

namespace Rector\Tests\Transform\Rector\Attribute\ReplaceAttributeUseWithAnotherAttributeRector\Fixture;

class SomeClass
{
    #[\FooBar('some-parameter')]
    public string $name;
}

?>
----
<?php

namespace Rector\Tests\Transform\Rector\Attribute\ReplaceAttributeUseWithAnotherAttributeRector\Fixture;

class SomeClass
{
    #[\BarFoo('some-parameter')]
    public string $name;
}

?>
