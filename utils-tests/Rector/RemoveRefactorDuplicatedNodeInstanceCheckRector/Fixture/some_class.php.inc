<?php

namespace Rector\Utils\Tests\Rector\RemoveRefactorDuplicatedNodeInstanceCheckRector\Fixture;

use PhpParser\Node;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class SomeClass extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
    }

    public function getNodeTypes(): array
    {
        return [Node\Stmt\ClassMethod::class];
    }

    /**
     * @param Node\Stmt\ClassMethod $node
     */
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

final class SomeClass extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
    }

    public function getNodeTypes(): array
    {
        return [Node\Stmt\ClassMethod::class];
    }

    /**
     * @param Node\Stmt\ClassMethod $node
     */
    public function refactor(Node $node)
    {
    }
}

?>
