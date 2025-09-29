<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\DocblockReturnArrayFromDirectArrayInstanceRector\Fixture;

/**
 * @return array
 */
function overrideBareArray()
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
function overrideBareArray()
{
    return [
        'first'  => [true],
        'second' => [false],
    ];
}

?>
