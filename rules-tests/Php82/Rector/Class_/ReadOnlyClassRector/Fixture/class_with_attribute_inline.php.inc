<?php

namespace Rector\Tests\Php82\Rector\Class_\ReadOnlyClassRector\Fixture;

#[SomeAttribute]final class ClassWithAttributeInline
{
    private readonly string $property;
}

?>
-----
<?php

namespace Rector\Tests\Php82\Rector\Class_\ReadOnlyClassRector\Fixture;

#[SomeAttribute]
final readonly class ClassWithAttributeInline
{
    private string $property;
}

?>
