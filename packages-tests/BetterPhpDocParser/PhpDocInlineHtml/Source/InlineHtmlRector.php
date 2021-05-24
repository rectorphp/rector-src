<?php

namespace Rector\Tests\BetterPhpDocParser\PhpDocInlineHtml;

use PhpParser\Node;
use PHPStan\PhpDocParser\Ast\PhpDoc\VarTagValueNode;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use Rector\Core\Rector\AbstractRector;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;
use ViewScopeRector\Inferer\Rocket\FileLocator;
use ViewScopeRector\Inferer\Rocket\ViewFileLocator;

class InlineHtmlRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Defines a @var on inline html.', [new CodeSample('', '')]);
    }

    public function getNodeTypes(): array
    {
        return [Node\Stmt\InlineHTML::class];
    }

    /**
     * @param Node\Stmt\InlineHTML $inlineHtml
     */
    public function refactor(Node $inlineHtml): ?Node
    {
        if ($inlineHtml->getAttribute(AttributeKey::PREVIOUS_NODE) === null) {
            $phpDocInfo = $this->phpDocInfoFactory->createFromNodeOrEmpty($inlineHtml);
            $phpDocInfo->addTagValueNode(new VarTagValueNode(new IdentifierTypeNode('string'), '$hello', ''));
        }

        return null;
    }
}
