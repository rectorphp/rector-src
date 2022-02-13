<?php

namespace Rector\Tests\Php74\Rector\Property\TypedPropertyRector\FixtureUnionIntersectionTypes;

final class RemoveArrayOfMixedTypeDocInMultipleWithNull
{
    /**
     * @var string|mixed[]|null
     */
    private $property;
}

?>
-----
<?php

namespace Rector\Tests\Php74\Rector\Property\TypedPropertyRector\FixtureUnionIntersectionTypes;

final class RemoveArrayOfMixedTypeDocInMultipleWithNull
{
    private array|string|null $property = null;
}

?>
