<?php

function foo($foo) { $foo(); }

foo(static fn() => 1 == 1 ? false : null);

foo(fn() => false);

?>
-----
<?php

function foo($foo) { $foo(); }

foo(static fn(): ?string => 1 == 1 ? false : null);

foo(fn(): int => false);

?>
