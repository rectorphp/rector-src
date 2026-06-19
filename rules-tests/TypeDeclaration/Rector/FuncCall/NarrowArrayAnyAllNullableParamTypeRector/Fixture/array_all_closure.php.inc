<?php

namespace Rector\Tests\TypeDeclaration\Rector\FuncCall\NarrowArrayAnyAllNullableParamTypeRector\Fixture;

function arrayAllClosure(): bool
{
    /** @var int[] $numbers */
    $numbers = [1, 2, 3];

    return array_all($numbers, function (?int $number): bool {
        return $number > 0;
    });
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\FuncCall\NarrowArrayAnyAllNullableParamTypeRector\Fixture;

function arrayAllClosure(): bool
{
    /** @var int[] $numbers */
    $numbers = [1, 2, 3];

    return array_all($numbers, function (int $number): bool {
        return $number > 0;
    });
}

?>
