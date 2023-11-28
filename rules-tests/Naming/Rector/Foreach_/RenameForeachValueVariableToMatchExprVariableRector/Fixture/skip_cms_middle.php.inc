<?php

namespace Rector\Tests\Naming\Rector\Foreach_\RenameForeachValueVariableToMatchExprVariableRector\Fixture;

class SkipCmsMiddle
{
    public function run()
    {
        foreach ($cmsBlocks as $cmsBlock) {
        }
    }
}

?>
