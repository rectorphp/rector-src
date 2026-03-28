<?php

namespace Rector\Tests\DeadCode\Rector\Expression\RemoveDeadStmtRector\Fixture;

is_string('foo')
    |> assert(...);

sprintf('echo "%s"', 'foo')
    |> passthru(...);

?>
