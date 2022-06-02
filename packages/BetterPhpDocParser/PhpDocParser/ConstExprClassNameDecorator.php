<?php

declare(strict_types=1);

namespace Rector\BetterPhpDocParser\PhpDocParser;

use PhpParser\Node as PhpNode;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprNode;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstFetchNode;
use PHPStan\PhpDocParser\Ast\Node;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocNode;
use Rector\BetterPhpDocParser\ValueObject\PhpDocAttributeKey;
use Rector\Core\Configuration\CurrentNodeProvider;
use Rector\Core\Exception\ShouldNotHappenException;
use Rector\StaticTypeMapper\Naming\NameScopeFactory;
use Symplify\Astral\PhpDocParser\PhpDocNodeTraverser;

/**
 * Decorate node with fully qualified class name for const epxr,
 * e.g. Direction::*
 */
final class ConstExprClassNameDecorator
{
    public function __construct(
        private CurrentNodeProvider $currentNodeProvider,
        private NameScopeFactory $nameScopeFactory,
        private PhpDocNodeTraverser $phpDocNodeTraverser
    ) {
    }

    public function decorate(PhpDocNode $phpDocNode): void
    {
        $phpNode = $this->currentNodeProvider->getNode();

        if (! $phpNode instanceof PhpNode) {
            throw new ShouldNotHappenException();
        }

        $this->phpDocNodeTraverser->traverseWithCallable($phpDocNode, '', function (Node $node) use (
            $phpNode
        ): int|Node|null {
            if (! $node instanceof ConstExprNode) {
                return null;
            }

            $className = $this->resolveFullyQualifiedClass($node, $phpNode);
            if ($className === null) {
                return null;
            }

            $node->setAttribute(PhpDocAttributeKey::RESOLVED_CLASS, $className);
            return $node;
        });
    }

    private function resolveFullyQualifiedClass(ConstExprNode $constExprNode, PhpNode $node): ?string
    {
        if (! $constExprNode instanceof ConstFetchNode) {
            return null;
        }

        $nameScope = $this->nameScopeFactory->createNameScopeFromNodeWithoutTemplateTypes($node);
        return $nameScope->resolveStringName($constExprNode->className);
    }
}
