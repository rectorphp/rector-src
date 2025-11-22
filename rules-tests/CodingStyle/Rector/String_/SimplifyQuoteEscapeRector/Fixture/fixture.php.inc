<?php

namespace Rector\Tests\CodingStyle\Rector\String_\SimplifyQuoteEscapeRector\Fixture;

class Fixture
{
    public function run()
    {
         $name = "\" Tom";
         $name = '\' Sara';
    }
}

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\String_\SimplifyQuoteEscapeRector\Fixture;

class Fixture
{
    public function run()
    {
         $name = '" Tom';
         $name = "' Sara";
    }
}

?>
