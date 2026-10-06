<?php

namespace Rector\Tests\CodingStyle\Rector\String_\SimplifyQuoteEscapeRector\Fixture;

class AccentedChars
{
    public function run()
    {
         $message = 'Merci d\'insérer un message.';
         $message = "Il a dit \"été\"";
    }
}

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\String_\SimplifyQuoteEscapeRector\Fixture;

class AccentedChars
{
    public function run()
    {
         $message = "Merci d'insérer un message.";
         $message = 'Il a dit "été"';
    }
}

?>
