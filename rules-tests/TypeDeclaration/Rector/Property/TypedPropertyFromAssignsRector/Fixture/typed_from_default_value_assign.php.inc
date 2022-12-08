<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector\Fixture;

final class TypedFromDefaultValueAssign
{
    private $name = [];
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Property\TypedPropertyFromAssignsRector\Fixture;

final class TypedFromDefaultValueAssign
{
    private array $name = [];
}

?>
