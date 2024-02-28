<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedPromotedPropertyRector\Fixture;

class SkipNoParams
{
    public function __construct()
    {
        echo 'some stmt';
    }
}

?>
