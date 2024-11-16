<?php

namespace Rector\Tests\CodingStyle\Rector\Stmt\NewlineAfterStatementRector\Fixture;

if (mt_rand(0, 1)) { ?>
    <div>
        <h1>Hi</h1>
    </div>

<?php } else { ?>
    <div>
        <h1>Bye</h1>
    </div>

<?php } ?>

End of file
