<?php

namespace Rector\Tests\CodingStyle\Rector\Stmt\NewlineAfterStatementRector\Fixture;

class NoNewLine
{
    public function run()
    {
    }
    public function run2()
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\Stmt\NewlineAfterStatementRector\Fixture;

class NoNewLine
{
    public function run()
    {
    }

    public function run2()
    {
    }
}

?>
