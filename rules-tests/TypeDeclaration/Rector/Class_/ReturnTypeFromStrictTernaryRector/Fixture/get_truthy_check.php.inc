<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\ReturnTypeFromStrictTernaryRector\Fixture;

function getThruthyCheck($number, $number2)
{
    return $number ? false : (bool) $number2;
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\ReturnTypeFromStrictTernaryRector\Fixture;

function getThruthyCheck($number, $number2): bool
{
    return $number ? false : (bool) $number2;
}

?>
