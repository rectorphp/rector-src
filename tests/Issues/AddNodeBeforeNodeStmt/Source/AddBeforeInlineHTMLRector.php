<?php

declare(strict_types=1);

namespace Rector\Core\Tests\Issues\AddNodeBeforeNodeStmt\Source;

use PhpParser\Node;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Expression;
use PHPStan\PhpDocParser\Ast\PhpDoc\VarTagValueNode;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
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

        $expression1 = new Expression(
            new Assign(
                new Variable('test'), new String_('test')
            )
        );

        $phpDocInfo = $this->phpDocInfoFactory->createEmpty($expression1);
        $phpDocInfo->addTagValueNode(
            new VarTagValueNode(
                new IdentifierTypeNode('string'),
                '$container',
                ''
            )
        );

        $this->nodesToAddCollector->addNodeBeforeNode(
            $expression1,
            $firstStmt
        );

        $expression2 = new Expression(
            new Assign(
                new Variable('test2'), new String_('test2')
            )
        );

        $phpDocInfo = $this->phpDocInfoFactory->createEmpty($expression2);
        $phpDocInfo->addTagValueNode(
            new VarTagValueNode(
                new IdentifierTypeNode('string'),
                '$container',
                ''
            )
        );

        $this->nodesToAddCollector->addNodeBeforeNode(
            $expression2,
            $firstStmt
        );

        $this->justAdded[$this->file->getFilePath()] = true;

        return $node;
    }
}
