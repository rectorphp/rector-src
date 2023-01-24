<?php

declare(strict_types=1);

namespace Rector\Core\Tests\Issues\ReturnArrayNodeAfterNopStmt\Source;

use PhpParser\Comment;
use PhpParser\Node;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Echo_;
use PhpParser\Node\Stmt\If_;
use PhpParser\Node\Stmt\Nop;
use PHPStan\PhpDocParser\Ast\PhpDoc\VarTagValueNode;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use Rector\Core\Rector\AbstractRector;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\PostRector\Collector\NodesToAddCollector;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

class ArrayAddNextNopRector extends AbstractRector
{
    public function __construct(private readonly NodesToAddCollector $nodesToAddCollector)
    {
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('uff', []);
    }

    public function getNodeTypes(): array
    {
        return [
            Nop::class,
        ];
    }

    public function refactor(Node $node)
    {
        $echo = new Echo_([new String_("this is new stmt after Nop")]);

        $phpDocInfo = $this->phpDocInfoFactory->createEmpty($echo);
        $phpDocInfo->addTagValueNode(
            new VarTagValueNode(
                new IdentifierTypeNode('string'),
                '$container',
                ''
            )
        );

        return [
            $node,
            $echo,
        ];
    }
}
