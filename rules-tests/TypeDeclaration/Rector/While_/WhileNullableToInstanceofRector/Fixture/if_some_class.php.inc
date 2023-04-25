<?php

namespace Rector\Tests\TypeDeclaration\Rector\While_\WhileNullableToInstanceofRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\While_\WhileNullableToInstanceofRector\Source\CheckedClass;

class IfSomeClass
{
    function run(?CheckedClass $someClass)
    {
        if ($someClass !== null) {
            // do something
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\While_\WhileNullableToInstanceofRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\While_\WhileNullableToInstanceofRector\Source\CheckedClass;

class IfSomeClass
{
    function run(?CheckedClass $someClass)
    {
        if ($someClass instanceof \Rector\Tests\TypeDeclaration\Rector\While_\WhileNullableToInstanceofRector\Source\CheckedClass) {
            // do something
        }
    }
}

?>
