<?php

namespace Rector\Tests\Naming\Rector\ClassMethod\RenameParamToMatchTypeRector\Fixture;

use PhpParser\Node\Stmt\Foreach_;
use PhpParser\Node\Expr\Variable;

class UsedInArrowFunctionParam
{
    public function run(Foreach_ $node)
    {
        array_map(
          fn (Variable $node) => $node,
          []
        );

        var_dump($node);
    }
}

?>
-----
<?php

namespace Rector\Tests\Naming\Rector\ClassMethod\RenameParamToMatchTypeRector\Fixture;

use PhpParser\Node\Stmt\Foreach_;
use PhpParser\Node\Expr\Variable;

class UsedInArrowFunctionParam
{
    public function run(Foreach_ $foreach)
    {
        array_map(
          fn (Variable $variable) => $variable,
          []
        );

        var_dump($foreach);
    }
}

?>
