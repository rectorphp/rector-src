<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\DocblockReturnArrayFromDirectArrayInstanceRector\Fixture;

function FalseAndTrue()
{
    return [
        'first'  => [true],
        'second' => [false],
    ];
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\DocblockReturnArrayFromDirectArrayInstanceRector\Fixture;

/**
 * @return array<string, bool[]>
 */
function FalseAndTrue()
{
    return [
        'first'  => [true],
        'second' => [false],
    ];
}

?>
