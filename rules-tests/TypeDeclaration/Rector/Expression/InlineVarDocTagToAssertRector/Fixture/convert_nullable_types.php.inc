<?php

/** @var ?string $foo */
$foo = getNullableString();

/** @var ?Foo $foo */
$foo = getNullableFoo();

?>
-----
<?php

$foo = getNullableString();
assert(is_string($foo) || $foo === null);

$foo = getNullableFoo();
assert($foo instanceof Foo || $foo === null);

?>
