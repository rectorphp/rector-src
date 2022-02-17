<?php

namespace Rector\Tests\DeadCode\Rector\Cast\RecastingRemovalRector\Fixture;

final class SkipTypeByVardoc
{    
    public function test() {
        /** @var int $int */
        $int = '';
    	return (int) $int;
    }
}
?>
-----
