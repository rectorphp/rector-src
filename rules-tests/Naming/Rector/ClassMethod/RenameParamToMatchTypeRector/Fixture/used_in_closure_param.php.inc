<?php

namespace Rector\Tests\Naming\Rector\ClassMethod\RenameParamToMatchTypeRector\Fixture;

use PhpParser\Node\Stmt\Foreach_;
use PhpParser\Node\Expr\Variable;

class UsedInClosureParam
{
    public function run(Foreach_ $node)
    {
        array_map(
          function (Variable $node) {
            return $node;
          },
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

class UsedInClosureParam
{
    public function run(Foreach_ $foreach)
    {
        array_map(
          function (Variable $variable) {
            return $variable;
          },
          []
        );

        var_dump($foreach);
    }
}

?>
