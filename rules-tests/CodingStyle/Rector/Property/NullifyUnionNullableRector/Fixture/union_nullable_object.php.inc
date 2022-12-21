<?php

namespace Rector\Tests\CodingStyle\Rector\Property\VarAnnotationIncorrectNullableRector\Fixture;

final class UnionNullableObject
{
    private null|stdClass $propertyA = null;
    private stdClass|null $propertyB = null;
}

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\Property\VarAnnotationIncorrectNullableRector\Fixture;

final class UnionNullableObject
{
    private ?stdClass $propertyA = null;
    private ?stdClass $propertyB = null;
}

?>
