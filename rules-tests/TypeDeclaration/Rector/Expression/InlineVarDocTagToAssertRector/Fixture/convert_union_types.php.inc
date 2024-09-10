<?php

/** @var string|false $foo */
$foo = getStringOrFalse();

/** @var string|int|null $foo */
$foo = getStringOrFalse();

/** @var int|float|bool|string|array $foo */
$foo = getMultipleTypes();

/** @var string|Foo $foo */
$foo = getStringOrFoo();

/** @var Foo|Bar $foo */
$foo = getFooOrBar();

/** @var \Foo|\Bar $foo */
$foo = getFooOrBar();

?>
-----
<?php

$foo = getStringOrFalse();
assert(is_string($foo) || $foo === false);

$foo = getStringOrFalse();
assert(is_string($foo) || is_int($foo) || $foo === null);

$foo = getMultipleTypes();
assert(is_int($foo) || is_float($foo) || is_bool($foo) || is_string($foo) || is_array($foo));

$foo = getStringOrFoo();
assert(is_string($foo) || $foo instanceof Foo);

$foo = getFooOrBar();
assert($foo instanceof Foo || $foo instanceof Bar);

$foo = getFooOrBar();
assert($foo instanceof \Foo || $foo instanceof \Bar);

?>
