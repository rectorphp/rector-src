<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\AddParamTypeToRefactorMethodRector\Fixture;

use PhpParser\Node;
use PhpParser\Node\Expr\BinaryOp\Identical;
use PhpParser\Node\Expr\BinaryOp\NotIdentical;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class AddMultipleNodeTypes extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
    }

    public function getNodeTypes(): array
    {
        return [Identical::class, NotIdentical::class];
    }

    public function refactor(Node $node): ?Node
    {
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\AddParamTypeToRefactorMethodRector\Fixture;

use PhpParser\Node;
use PhpParser\Node\Expr\BinaryOp\Identical;
use PhpParser\Node\Expr\BinaryOp\NotIdentical;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class AddMultipleNodeTypes extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
    }

    public function getNodeTypes(): array
    {
        return [Identical::class, NotIdentical::class];
    }

    /**
     * @param Identical|NotIdentical $node
     */
    public function refactor(Node $node): ?Node
    {
    }
}

?>
