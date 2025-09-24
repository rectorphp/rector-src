<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddParamArrayDocblockFromAssignsParamToParamReferenceRector\Fixture;

final class SomeClass
{
    public function run(array &$items)
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
    public function run(array &$items)
    {
        $items[] = 'John';
    }
}

?>
