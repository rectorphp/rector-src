<?php

namespace Rector\Tests\Php74\Rector\FuncCall\HebrevcToNl2brHebrevRector\Fixture;

class Fixture
{
    public function run($str)
    {
        hebrevc($str);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php74\Rector\FuncCall\HebrevcToNl2brHebrevRector\Fixture;

class Fixture
{
    public function run($str)
    {
        nl2br(hebrev($str));
    }
}

?>
