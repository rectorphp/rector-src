<?php

namespace Rector\Tests\Php74\Rector\Property\TypedPropertyRector\Fixture;

final class DoNotRemoveNullableArrayStringKeyMixedNullableDoc
{
    /** @var array<string,mixed>|null */
    private $property;
}

?>
-----
<?php

namespace Rector\Tests\Php74\Rector\Property\TypedPropertyRector\Fixture;

final class DoNotRemoveNullableArrayStringKeyMixedNullableDoc
{
    /** @var array<string,mixed>|null */
    private ?array $property = null;
}

?>
