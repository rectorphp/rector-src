<?php

namespace Rector\Tests\DeadCode\Rector\Block\ReplaceBlockToItsStmtsRector\Fixture;

{
    echo "statement 1";
    echo PHP_EOL;
    echo "statement 2";
}

?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\Block\ReplaceBlockToItsStmtsRector\Fixture;

echo "statement 1";
echo PHP_EOL;
echo "statement 2";

?>
