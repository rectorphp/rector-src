<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\ReturnTypeFromStrictTernaryRector\Fixture;

function getValue($number)
{
    return $number ? 'a' : 'b';
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\ReturnTypeFromStrictTernaryRector\Fixture;

function getValue($number): string
{
    return $number ? 'a' : 'b';
}

?>
