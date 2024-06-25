<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ArrowFunction\AddArrowFunctionReturnTypeRector\Fixture;

final class ReturnArray
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

namespace Rector\Tests\TypeDeclaration\Rector\ArrowFunction\AddArrowFunctionReturnTypeRector\Fixture;

final class ReturnArray
{
    public function getArray()
    {
        $items = fn (): array => [1, 2, 3];
    }
}

?>
