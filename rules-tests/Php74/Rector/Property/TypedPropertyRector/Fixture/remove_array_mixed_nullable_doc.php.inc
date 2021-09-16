<?php

namespace Rector\Tests\Php74\Rector\Property\TypedPropertyRector\Fixture;

final class RemoveArrayMixedNullable
{
    /** @var array<mixed>|null */
    private $property;
}

?>
-----
<?php

namespace Rector\Tests\Php74\Rector\Property\TypedPropertyRector\Fixture;

final class RemoveArrayMixedNullable
{
    private ?array $property = null;
}

?>
