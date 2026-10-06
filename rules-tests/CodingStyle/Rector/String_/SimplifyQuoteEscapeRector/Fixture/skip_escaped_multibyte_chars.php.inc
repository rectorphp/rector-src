<?php

namespace Rector\Tests\CodingStyle\Rector\String_\SimplifyQuoteEscapeRector\Fixture;

class SkipEscapedMultibyteChars
{
    public function run()
    {
         $csvHeader = "\xEF\xBB\xBF\"id\"";
         $nonBreakingSpace = "\u{00A0}\"";
    }
}
