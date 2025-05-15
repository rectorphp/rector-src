<?php

namespace Rector\Tests\Php80\Rector\NotIdentical\StrContainsRector\Fixture;

class SkipMultibyteStrstrWithDynamicEncoding
{
    public function run(?string $encoding = 'UTF-8')
    {
        $isMatch = mb_strstr('happy 😊 euro €', 'a', false, $encoding) !== false;
    }
}

?>
