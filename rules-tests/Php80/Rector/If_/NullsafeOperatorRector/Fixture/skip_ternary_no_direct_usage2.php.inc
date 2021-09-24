<?php

namespace Rector\Tests\Php80\Rector\If_\NullsafeOperatorRector\Fixture;

final class SkipTernaryNoDirectUsage2
{
    public function run()
    {
        return $someObject === null ? null : $this->parse($someObject);
    }
    
    public function parse($someObject)
    {
    }
}
?>
