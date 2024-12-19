<?php

namespace Rector\Tests\Php82\Rector\Class_\ReadOnlyClassRector\Fixture;

#[SomeAttribute]
final class ClassWithAttribute
{
    private readonly string $property;
}

?>
-----
<?php

namespace Rector\Tests\Php82\Rector\Class_\ReadOnlyClassRector\Fixture;

#[SomeAttribute]
final readonly class ClassWithAttribute
{
    private string $property;
}

?>
