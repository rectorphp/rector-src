<?php

/** @var Foo&Bar $foo */
$foo = getFooAndBar();

/** @var \Foo&\Bar $foo */
$foo = getFooAndBar();

?>
-----
<?php

$foo = getFooAndBar();
assert($foo instanceof Foo && $foo instanceof Bar);

$foo = getFooAndBar();
assert($foo instanceof \Foo && $foo instanceof \Bar);

?>
