<?php

namespace Rector\Tests\PhpParser\Printer\CommentPreserving\Fixture;

class KeepMultilineSingleAsterisk
{
    public function baz($baz)
    {
        /*
        should
        not
        be changed
        */

        return $baz;
    }
}
