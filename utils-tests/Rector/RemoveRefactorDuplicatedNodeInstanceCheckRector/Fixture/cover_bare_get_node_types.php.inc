<?php

namespace Rector\Utils\Tests\Rector\RemoveRefactorDuplicatedNodeInstanceCheckRector\Fixture;

use PhpParser\Node;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class CoverBareGetNodeTypes extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
    }

    public function getNodeTypes(): array
    {
        return [Node\Stmt\ClassMethod::class];
    }

    public function refactor(Node $node)
    {
        if (! $node instanceof Node\Stmt\ClassMethod) {
            return null;
        }
    }
}

?>
-----
<?php

namespace Rector\Utils\Tests\Rector\RemoveRefactorDuplicatedNodeInstanceCheckRector\Fixture;

use PhpParser\Node;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class CoverBareGetNodeTypes extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
    }

    public function getNodeTypes(): array
    {
        return [Node\Stmt\ClassMethod::class];
    }

    public function refactor(Node $node)
    {
    }
}

?>
