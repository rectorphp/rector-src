<?php

namespace Rector\Tests\CodingStyle\Rector\String_\SimplifyQuoteEscapeRector\Fixture;

class SkipInvalidUtf8
{
    public function run()
    {
         $latin1 = 'Merci d\'insérer un message.';
    }
}
