<?php

namespace Rector\Tests\TypeDeclaration\Rector\While_\WhileNullableToInstanceofRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\While_\WhileNullableToInstanceofRector\Source\CheckedClass;

class DoWhileSomeClass
{
    function run(?CheckedClass $someClass)
    {
        do {
            // do something
        } while ($someClass !== null);
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\While_\WhileNullableToInstanceofRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\While_\WhileNullableToInstanceofRector\Source\CheckedClass;

class DoWhileSomeClass
{
    function run(?CheckedClass $someClass)
    {
        do {
            // do something
        } while ($someClass instanceof \Rector\Tests\TypeDeclaration\Rector\While_\WhileNullableToInstanceofRector\Source\CheckedClass);
    }
}

?>
