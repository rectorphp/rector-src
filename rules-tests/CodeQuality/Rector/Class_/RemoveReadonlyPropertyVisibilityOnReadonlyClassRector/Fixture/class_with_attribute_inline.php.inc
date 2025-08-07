<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\RemoveReadonlyPropertyVisibilityOnReadonlyClassRector\Fixture;

#[SomeAttribute]final readonly class ClassWithAttributeInline
{
    private readonly string $property;
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\RemoveReadonlyPropertyVisibilityOnReadonlyClassRector\Fixture;

#[SomeAttribute]final readonly class ClassWithAttributeInline
{
    private string $property;
}

?>
