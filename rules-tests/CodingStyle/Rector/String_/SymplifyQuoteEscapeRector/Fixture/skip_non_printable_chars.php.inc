<?php

namespace Rector\Tests\CodingStyle\Rector\String_\SymplifyQuoteEscapeRector\Fixture;

final class SkipNonPrintableChars
{
    public function run()
    {
        $data = lz4_compress( $data, 1, "\x18\x4d\x22\x04" );

        return $data;
    }
}

?>
