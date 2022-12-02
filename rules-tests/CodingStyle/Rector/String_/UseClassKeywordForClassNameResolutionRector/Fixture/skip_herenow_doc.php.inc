<?php

namespace Rector\Tests\CodingStyle\Rector\String_\UseClassKeywordForClassNameResolutionRector\Fixture;

class SkipHereNowDoc
{
    public function run()
    {
        $variable = <<<'CODE_SAMPLE'
<?php if (Rector\Tests\CodingStyle\Rector\String_\UseClassKeywordForClassNameResolutionRector\Fixture\Fixture::staticCall()) { ?>
CODE_SAMPLE;
    }
}
