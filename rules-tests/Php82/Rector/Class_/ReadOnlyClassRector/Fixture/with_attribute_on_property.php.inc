<?php

namespace Rector\Tests\Php82\Rector\Class_\ReadOnlyClassRector\Fixture;

final class WithAttributeOnProperty
{
    #[MyAttr]
    public readonly string $id;
}

?>
-----
<?php

namespace Rector\Tests\Php82\Rector\Class_\ReadOnlyClassRector\Fixture;

final readonly class WithAttributeOnProperty
{
    #[MyAttr]
    public string $id;
}

?>
