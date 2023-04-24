<?php

namespace Rector\Tests\TypeDeclaration\Rector\While_\WhileNullableToInstanceofRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\While_\WhileNullableToInstanceofRector\Source\CheckedClass;

final class SilentCondition
{
    function run(?CheckedClass $someClass)
    {
        while ($someClass) {
            // do something
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\While_\WhileNullableToInstanceofRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\While_\WhileNullableToInstanceofRector\Source\CheckedClass;

final class SilentCondition
{
    function run(?CheckedClass $someClass)
    {
        while ($someClass instanceof \Rector\Tests\TypeDeclaration\Rector\While_\WhileNullableToInstanceofRector\Source\CheckedClass) {
            // do something
        }
    }
}

?>
