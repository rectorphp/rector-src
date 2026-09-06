<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddParamArrayDocblockFromAssignsParamToParamReferenceRector\Fixture;

final class OverrideDummyArrayParam
{
    /**
     * @param array $items
     */
    private function run(array &$items)
    {
        $items[] = 'John';
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddParamArrayDocblockFromAssignsParamToParamReferenceRector\Fixture;

final class OverrideDummyArrayParam
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
