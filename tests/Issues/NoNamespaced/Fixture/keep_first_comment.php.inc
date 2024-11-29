<?php

/**
 * @license some license docblock
 */
use PhpParser\Node\Scalar\String_ as Foo;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Stmt\Expression as Bar;

new Bar(new Array_([]));

?>
-----
<?php

/**
 * @license some license docblock
 */

use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Stmt\Expression as Bar;

new Bar(new Array_([]));

?>
