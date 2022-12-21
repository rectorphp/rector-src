<?php

namespace Rector\Tests\CodingStyle\Rector\Property\VarAnnotationIncorrectNullableRector\Fixture;

final class UnionNullableProperty
{
    private null|array $propertyA = null;
    private array|null $propertyB = null;
}

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\Property\VarAnnotationIncorrectNullableRector\Fixture;

final class UnionNullableProperty
{
    private ?array $propertyA = null;
    private ?array $propertyB = null;
}

?>
