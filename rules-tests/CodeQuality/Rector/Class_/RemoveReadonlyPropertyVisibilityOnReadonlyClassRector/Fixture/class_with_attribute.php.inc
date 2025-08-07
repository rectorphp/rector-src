<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\RemoveReadonlyPropertyVisibilityOnReadonlyClassRector\Fixture;

#[SomeAttribute]
final readonly class ClassWithAttribute
{
    private readonly string $property;
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\RemoveReadonlyPropertyVisibilityOnReadonlyClassRector\Fixture;

#[SomeAttribute]
final readonly class ClassWithAttribute
{
    private string $property;
}

?>
