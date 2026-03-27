<?php

is_string('foo')
    |> assert(...);

sprintf('echo "%s"', 'foo')
    |> passthru(...);

?>
