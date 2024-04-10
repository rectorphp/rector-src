<?php

namespace Rector\Tests\TypeDeclaration\Rector\ArrowFunction\AddArrowFunctionReturnTypeRector\Fixture;

class ReturnByArrayShapeType
{
    /**
     * @param array<int, array{bar: int}> $values
     */
    private function foo(array $values): void
    {
        $bars = array_map(fn($value) => $value['bar'], $values);
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ArrowFunction\AddArrowFunctionReturnTypeRector\Fixture;

class ReturnByArrayShapeType
{
    /**
     * @param array<int, array{bar: int}> $values
     */
    private function foo(array $values): void
    {
        $bars = array_map(fn($value): int => $value['bar'], $values);
    }
}

?>
