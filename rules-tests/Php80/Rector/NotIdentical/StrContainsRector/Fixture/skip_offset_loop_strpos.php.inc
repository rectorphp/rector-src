<?php

namespace Rector\Tests\Php80\Rector\NotIdentical\StrContainsRector\Fixture;

class SkipOffsetLoopStrpos
{
    public function run(string $content, string $needle)
    {
        $offset = 0;
        while (strpos($content, $needle, $offset) !== false) {
            $offset = strpos($content, $needle, $offset) + 1;
        }
    }
}
?>
