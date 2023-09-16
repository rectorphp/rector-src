<?php

namespace Rector\Tests\TypeDeclaration\Rector\While_\WhileNullableToInstanceofRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\While_\WhileNullableToInstanceofRector\Source\CheckedClass;

class SkipPhpDoc
{
    function run($someClass)
    {
        /** @var SomeClass $someClass */
        while ($someClass !== null) {
            // do something
        }
    }
}

?>
