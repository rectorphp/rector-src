<?php

namespace Rector\Tests\Php82\Rector\Class_\ReadOnlyClassRector\Fixture;

final class ImplicitPublicReadonlyProperty
{
    readonly string $foo;

    public function __construct()
    {
        $this->foo = 'bar';
    }
}

?>
-----
<?php

namespace Rector\Tests\Php82\Rector\Class_\ReadOnlyClassRector\Fixture;

final readonly class ImplicitPublicReadonlyProperty
{
    public string $foo;

    public function __construct()
    {
        $this->foo = 'bar';
    }
}

?>
