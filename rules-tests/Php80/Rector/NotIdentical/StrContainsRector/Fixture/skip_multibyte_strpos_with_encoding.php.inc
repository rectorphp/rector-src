<?php

namespace Rector\Tests\Php80\Rector\NotIdentical\StrContainsRector\Fixture;

class SkipMultibyteStrposWithEncoding
{
    public function run()
    {
        $isMatch = mb_strpos('happy 😊 euro €', 'a', 0, 'UTF-8') !== false;
    }
}

?>
