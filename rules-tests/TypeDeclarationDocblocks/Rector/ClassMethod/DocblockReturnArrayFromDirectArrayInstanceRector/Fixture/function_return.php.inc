<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\DocblockReturnArrayFromDirectArrayInstanceRector\Fixture;

function functionReturn()
{
    return [
        'key' => 'value',
    ];
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\DocblockReturnArrayFromDirectArrayInstanceRector\Fixture;

/**
 * @return array<string, string>
 */
function functionReturn()
{
    return [
        'key' => 'value',
    ];
}

?>
