<?php

namespace Rector\Tests\Php80\Rector\NotIdentical\StrContainsRector\Fixture;

class SkipMultibyteStrstrWithEncoding
{
    public function run()
    {
        $isMatch = mb_strstr('happy 😊 euro €', 'a', false, 'UTF-8') !== false;
    }
}

?>
