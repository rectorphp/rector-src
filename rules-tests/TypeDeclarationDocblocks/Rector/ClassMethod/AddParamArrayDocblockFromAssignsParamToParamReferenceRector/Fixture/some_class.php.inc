<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddParamArrayDocblockFromAssignsParamToParamReferenceRector\Fixture;

final class SomeClass
{
    private function run(array &$items)
    {
        $items[] = 'John';
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddParamArrayDocblockFromAssignsParamToParamReferenceRector\Fixture;

final class SomeClass
{
    /**
     * @param string[] $items
     */
    private function run(array &$items)
    {
        $items[] = 'John';
    }
}

?>
