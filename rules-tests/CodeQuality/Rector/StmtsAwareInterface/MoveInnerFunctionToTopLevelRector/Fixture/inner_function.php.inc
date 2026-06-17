<?php

namespace Rector\Tests\CodeQuality\Rector\StmtsAwareInterface\MoveInnerFunctionToTopLevelRector\Fixture;

function outer(): void
{
    function inner(): void
    {
        echo 'hello';
    }

    inner();
}

?>
-----
<?php

namespace Rector\Tests\CodeQuality\Rector\StmtsAwareInterface\MoveInnerFunctionToTopLevelRector\Fixture;

function inner(): void
{
    echo 'hello';
}
function outer(): void
{
    inner();
}

?>
