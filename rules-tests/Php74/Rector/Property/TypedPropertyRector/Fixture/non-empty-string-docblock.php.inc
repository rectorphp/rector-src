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

// what is expected code?
// should remain the same? delete part bellow ----- (included)

?>
