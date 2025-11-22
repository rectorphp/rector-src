<?php

namespace Rector\Tests\CodingStyle\Rector\String_\SimplifyQuoteEscapeRector\Fixture;

class Skip
{
    public function run()
    {
         $name = "\" Tom'";
         $name = '\' Sara"';
    }
}
