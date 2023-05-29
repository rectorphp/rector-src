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

final class AddBeforeInlineHTMLRector extends AbstractRector
{
    private array $justAdded = [];

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

        $node->stmts = array_merge([$expression1, $expression2], $node->stmts);

        $this->justAdded[$this->file->getFilePath()] = true;

        return $node;
    }
}
