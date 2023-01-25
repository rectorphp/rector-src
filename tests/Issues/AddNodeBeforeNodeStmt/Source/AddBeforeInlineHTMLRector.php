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
    private array $justAdded = [];

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
        if (isset($this->justAdded[$this->file->getFilePath()])) {
            return null;
        }

        $firstStmt = current($node->stmts);

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
        $this->justAdded[$this->file->getFilePath()] = true;
        return $node;
    }
}
