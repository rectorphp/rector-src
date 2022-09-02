<?php

function dummy() {
    $internal_check = false;
    $dummy_check = dummy_check();

    if ($dummy_check && !$internal_check) {
        $internal_check == true;
    }
}
?>
-----
<?php

namespace Rector\Tests\DeadCode\Rector\Expression\RemoveDeadStmtRector\Fixture;

function dummy() {
    dummy_check();
}
?>
