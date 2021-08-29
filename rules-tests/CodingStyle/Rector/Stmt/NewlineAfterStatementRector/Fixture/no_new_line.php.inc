<?php

namespace Rector\Tests\CodingStyle\Rector\Stmt\NewlineAfterStatementRector\Fixture;

class NoNewLine
{
    private $property;
    private $property2;
}

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\Stmt\NewlineAfterStatementRector\Fixture;

class NoNewLine
{
    private $property;

    private $property2;
}

?>
