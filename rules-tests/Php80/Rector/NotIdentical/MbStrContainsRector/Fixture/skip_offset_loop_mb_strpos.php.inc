<?php

namespace Rector\Tests\Php80\Rector\NotIdentical\MbStrContainsRector\Fixture;

class SkipOffsetLoopMbStrpos
{
    public function run(string $content, string $needle)
    {
        $offset = 0;
        while (mb_strpos($content, $needle, $offset) !== false) {
            $offset = mb_strpos($content, $needle, $offset) + 1;
        }
    }
}
?>
