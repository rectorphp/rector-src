<?php

namespace Rector\Tests\Php84\Rector\FuncCall\AddEscapeArgumentRector;

final class Fixture
{
    public function run($a, $b)
    {
        str_getcsv($a, $b);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php84\Rector\FuncCall\AddEscapeArgumentRector;

final class Fixture
{
    public function run($a, $b)
    {
        str_getcsv($a, $b, escape: '\\');
    }
}

?>
