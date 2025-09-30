<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\ClassMethod\AddParamArrayDocblockFromAssignsParamToParamReferenceRector\Fixture;

final class OverrideDummyArrayParam
{
    /**
     * @param array $items
     */
    public function run(array &$items)
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
    public function run(array &$items)
    {
        $items[] = 'John';
    }
}

?>
