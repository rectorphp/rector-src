<?php

use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Stmt\Expression;

/**
 * @var Expression $stmt
 * @var FuncCall $expr
 */
$expr = $stmt->expr;

?>
-----
<?php

use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Stmt\Expression;

/**
 * @var Expression $stmt
 */
$expr = $stmt->expr;
assert($expr instanceof FuncCall);

?>
