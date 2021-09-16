<?php

namespace Rector\Tests\Php74\Rector\Property\TypedPropertyRector\Fixture;

final class DoNotRemoveArrayStringNullable
{
    /** @var array<string>|null */
    private $property;
}

?>
-----
<?php

namespace Rector\Tests\Php74\Rector\Property\TypedPropertyRector\Fixture;

final class DoNotRemoveArrayStringNullable
{
    /** @var array<string>|null */
    private ?array $property = null;
}

?>
