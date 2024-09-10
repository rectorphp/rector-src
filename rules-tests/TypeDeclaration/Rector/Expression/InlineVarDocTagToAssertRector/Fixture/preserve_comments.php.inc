<?php

/**
 * @var string $foo
 * This is the string
 */
$foo = getString();

/**
 * We are going to do something
 * @var string $foo
 */
$foo = getString();

/**
 * @var string $foo
 * @deprecated
 */
$foo = getString();

/**
 * @var string $foo
 * @see getStringTest()
 * Please do not change
 */
$foo = getString();

/**
 * @var string $foo
 * @var int $bar
 */
$foo = getString();
$bar = getInt();

/** @var string $foo this is the string */
$foo = getString();

?>
-----
<?php

/**
 * @var string $foo
 * This is the string
 */
$foo = getString();

/**
 * We are going to do something
 */
$foo = getString();
assert(is_string($foo));

/**
 * @deprecated
 */
$foo = getString();
assert(is_string($foo));

/**
 * @see getStringTest()
 * Please do not change
 */
$foo = getString();
assert(is_string($foo));

/**
 * @var int $bar
 */
$foo = getString();
assert(is_string($foo));
$bar = getInt();

/** @var string $foo this is the string */
$foo = getString();

?>
