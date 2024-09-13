<?php

/** @var false $foo */
$foo = getFalse();

/** @var true $foo */
$foo = getTrue();

/** @var string $foo */
$foo = getString();

/** @var float $foo */
$foo = getFloat();

/** @var int $foo */
$foo = getInt();

/** @var bool $foo */
$foo = getBool();

/** @var null $foo */
$foo = getNull();

/** @var callable $foo */
$foo = getCallable();

/** @var object $foo */
$foo = getObject();

/** @var array $foo */
$foo = getArray();

/** @var iterable $foo */
$foo = getIterable();

?>
-----
<?php

$foo = getFalse();
assert($foo === false);

$foo = getTrue();
assert($foo === true);

$foo = getString();
assert(is_string($foo));

$foo = getFloat();
assert(is_float($foo));

$foo = getInt();
assert(is_int($foo));

$foo = getBool();
assert(is_bool($foo));

$foo = getNull();
assert($foo === null);

$foo = getCallable();
assert(is_callable($foo));

$foo = getObject();
assert(is_object($foo));

$foo = getArray();
assert(is_array($foo));

$foo = getIterable();
assert(is_iterable($foo));

?>
