<?php

namespace Rector\Tests\TypeDeclaration\Rector\While_\WhileNullableToInstanceofRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\While_\WhileNullableToInstanceofRector\Source\CheckedClass;

class ElseIfSomeClass
{
    function run(?CheckedClass $someClass)
    {
        if (rand(0, 1)) {
            // do something
        } elseif ($someClass !== null) {
            // do something
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\While_\WhileNullableToInstanceofRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\While_\WhileNullableToInstanceofRector\Source\CheckedClass;

class ElseIfSomeClass
{
    function run(?CheckedClass $someClass)
    {
        if (rand(0, 1)) {
            // do something
        } elseif ($someClass instanceof \Rector\Tests\TypeDeclaration\Rector\While_\WhileNullableToInstanceofRector\Source\CheckedClass) {
            // do something
        }
    }
}

?>
