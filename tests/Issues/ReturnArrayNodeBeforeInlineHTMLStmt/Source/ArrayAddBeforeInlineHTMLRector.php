<?php

declare(strict_types=1);

namespace Rector\Core\Tests\Issues\ReturnArrayNodeBeforeInlineHTMLStmt\Source;

use PhpParser\Node;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Echo_;
use PhpParser\Node\Stmt\InlineHTML;
use PHPStan\PhpDocParser\Ast\PhpDoc\VarTagValueNode;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use Rector\Core\Rector\AbstractRector;
use Rector\PostRector\Collector\NodesToAddCollector;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

class ArrayAddBeforeInlineHTMLRector extends AbstractRector
{
    private array $justAdded = [];

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
            InlineHTML::class,
        ];
    }

    public function refactor(Node $node)
    {
        if (isset($this->justAdded[$this->file->getFilePath()])) {
            return null;
        }

        $echo = new Echo_([new String_("this is new stmt before InlineHTML")]);

        $phpDocInfo = $this->phpDocInfoFactory->createEmpty($echo);
        $phpDocInfo->addTagValueNode(
            new VarTagValueNode(
                new IdentifierTypeNode('string'),
                '$container',
                ''
            )
        );

        $this->justAdded[$this->file->getFilePath()] = true;

        return [
            $echo,
            $echo,
            $node,
        ];
    }
}
