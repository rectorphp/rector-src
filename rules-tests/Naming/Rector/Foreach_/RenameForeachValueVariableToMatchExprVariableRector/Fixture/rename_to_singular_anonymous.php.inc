<?php

namespace Rector\Tests\Naming\Rector\Foreach_\RenameForeachValueVariableToMatchExprVariableRector\Fixture;

class RenameToSingularAnonymous
{
    public function run(array $anonymousClassNodes)
    {
        foreach ($anonymousClassNodes as $node) {
            echo $node;
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\Naming\Rector\Foreach_\RenameForeachValueVariableToMatchExprVariableRector\Fixture;

class RenameToSingularAnonymous
{
    public function run(array $anonymousClassNodes)
    {
        foreach ($anonymousClassNodes as $anonymousClassNode) {
            echo $anonymousClassNode;
        }
    }
}

?>
