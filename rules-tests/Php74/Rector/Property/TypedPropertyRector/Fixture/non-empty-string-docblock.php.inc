<?php

namespace Rector\Tests\Php74\Rector\Property\TypedPropertyRector\Fixture;

final class Foo
{
    /**
     * @var string
     * @psalm-var non-empty-string
     */
    private $name;
}
?>
-----
<?php

namespace Rector\Tests\Php74\Rector\Property\TypedPropertyRector\Fixture;

final class Foo
{
    /**
     * @psalm-var non-empty-string
     */
    private string $name;
}
?>
