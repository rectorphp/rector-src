<?php

namespace Rector\Tests\Php82\Rector\Class_\ReadOnlyClassRector\Fixture;

#[SomeAttribute]
final class ClassWithAttributeNewLine
{
    private readonly string $property;
}

?>
-----
<?php

namespace Rector\Tests\Php82\Rector\Class_\ReadOnlyClassRector\Fixture;

#[SomeAttribute]
final readonly class ClassWithAttributeNewLine
{
    private string $property;
}

?>
