<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromReturnDirectArrayRector\Fixture;

final class AddReturnArrayArrowFunction
{
    public function getArray()
    {
        $items = fn () => [1, 2, 3];
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromReturnDirectArrayRector\Fixture;

final class AddReturnArrayArrowFunction
{
    public function getArray()
    {
        $items = fn (): array => [1, 2, 3];
    }
}

?>
