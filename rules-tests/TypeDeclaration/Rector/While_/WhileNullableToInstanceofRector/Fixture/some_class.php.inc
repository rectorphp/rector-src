<?php

namespace Rector\Tests\TypeDeclaration\Rector\While_\WhileNullableToInstanceofRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\While_\WhileNullableToInstanceofRector\Source\CheckedClass;

class SomeClass
{
    function run(?CheckedClass $someClass)
    {
        while ($someClass !== null) {
            // do something
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\While_\WhileNullableToInstanceofRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\While_\WhileNullableToInstanceofRector\Source\CheckedClass;

class SomeClass
{
    function run(?CheckedClass $someClass)
    {
        while ($someClass instanceof \Rector\Tests\TypeDeclaration\Rector\While_\WhileNullableToInstanceofRector\Source\CheckedClass) {
            // do something
        }
    }
}

?>
