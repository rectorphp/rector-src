<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\DocblockReturnArrayFromDirectArrayInstanceRector\Fixture;

function FalseAndTrue()
{
    return [
        'first'  => [true],
        'second' => [false],
        'third'  => [true],
        'fourth' => [false],
    ];
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\DocblockReturnArrayFromDirectArrayInstanceRector\Fixture;

/**
 * @return array<string, true[]|false[]>
 */
function FalseAndTrue()
{
    return [
        'first'  => [true],
        'second' => [false],
        'third'  => [true],
        'fourth' => [false],
    ];
}

?>
