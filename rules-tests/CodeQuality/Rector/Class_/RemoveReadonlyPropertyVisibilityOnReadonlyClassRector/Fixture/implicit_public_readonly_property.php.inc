<?php

namespace Rector\Tests\CodeQuality\Rector\Class_\RemoveReadonlyPropertyVisibilityOnReadonlyClassRector\Fixture;

final readonly class ImplicitPublicReadonlyProperty
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

namespace Rector\Tests\CodeQuality\Rector\Class_\RemoveReadonlyPropertyVisibilityOnReadonlyClassRector\Fixture;

final readonly class ImplicitPublicReadonlyProperty
{
    public string $foo;

    public function __construct()
    {
        $this->foo = 'bar';
    }
}

?>
