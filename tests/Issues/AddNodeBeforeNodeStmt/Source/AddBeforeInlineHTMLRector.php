<?php

declare(strict_types=1);

namespace Rector\Core\Tests\Issues\AddNodeBeforeNodeStmt\Source;

use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\InlineHTML;
use Rector\Core\PhpParser\Node\CustomNode\FileWithoutNamespace;
use Rector\Core\Rector\AbstractRector;
use Rector\PostRector\Collector\NodesToAddCollector;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

class AddBeforeInlineHTMLRector extends AbstractRector
{
    private bool $justAdded = false;

    public function __construct(private readonly NodesToAddCollector $nodesToAddCollector)
    {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('before html', []);
    }

    public function getNodeTypes(): array
    {
        return [
            FileWithoutNamespace::class,
        ];
    }

    /**
     * @param FileWithoutNamespace $node
     */
    public function refactor(Node $node)
    {
        if ($this->justAdded) {
            return null;
        }

        $firstStmt = $node->stmts[0];

        $this->nodesToAddCollector->addNodeBeforeNode(
            new Expression(
                new Assign(
                    new Variable('test'), new String_('test')
                )
            ),
            $firstStmt
        );
        $this->nodesToAddCollector->addNodeBeforeNode(
            new Expression(
                new Assign(
                    new Variable('test2'), new String_('test2')
                )
            ),
            $firstStmt
        );
        $this->justAdded = true;
        return $node;
    }
}
