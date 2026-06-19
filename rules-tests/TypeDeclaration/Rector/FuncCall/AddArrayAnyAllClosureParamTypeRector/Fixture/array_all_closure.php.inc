<?php

namespace Rector\Tests\TypeDeclaration\Rector\FuncCall\AddArrayAnyAllClosureParamTypeRector\Fixture;

function arrayAllClosure()
{
    /** @var int[] $numbers */
    $numbers = doSomething();

    return array_all($numbers, function ($number): bool {
        return $number > 0;
    });
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\FuncCall\AddArrayAnyAllClosureParamTypeRector\Fixture;

function arrayAllClosure()
{
    /** @var int[] $numbers */
    $numbers = doSomething();

    return array_all($numbers, function (int $number): bool {
        return $number > 0;
    });
}

?>
