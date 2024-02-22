<?php

declare(strict_types=1);

namespace Rector\BetterPhpDocParser\PhpDocParser;

use Nette\Utils\Strings;
use PhpParser\Node as PhpNode;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstFetchNode;
use PHPStan\PhpDocParser\Ast\Node;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocNode;
use Rector\BetterPhpDocParser\Contract\PhpDocParser\PhpDocNodeDecoratorInterface;
use Rector\BetterPhpDocParser\ValueObject\PhpDocAttributeKey;
use Rector\PhpDocParser\PhpDocParser\PhpDocNodeTraverser;
use Rector\StaticTypeMapper\Naming\NameScopeFactory;

/**
 * Decorate node with fully qualified class name for annotation:
 * e.g. @ORM\Column(type=Types::STRING, length=100, nullable=false)
 */
final readonly class ArrayItemClassNameDecorator implements PhpDocNodeDecoratorInterface
{
    public function __construct(
        private NameScopeFactory $nameScopeFactory,
        private PhpDocNodeTraverser $phpDocNodeTraverser
    ) {
    }

    public function decorate(PhpDocNode $phpDocNode, PhpNode $phpNode): void
    {
        // iterating all phpdocs has big overhead. peek into the phpdoc to exit early
        if (! str_contains($phpDocNode->__toString(), '::')) {
            return;
        }

        $this->phpDocNodeTraverser->traverseWithCallable($phpDocNode, '', function (Node $node) use (
            $phpNode
        ): Node|null {
            if (! $node instanceof \Rector\BetterPhpDocParser\PhpDoc\ArrayItemNode) {
                return null;
            }

            if (! is_string($node->value)) {
                return null;
            }

            $splitScopeResolution = explode('::', $node->value);
            if (count($splitScopeResolution) !== 2) {
                return null;
            }

            $firstName = $splitScopeResolution[0];
            $constFetchNode = new ConstFetchNode($firstName, $firstName);

            $className = $this->resolveFullyQualifiedClass($constFetchNode, $phpNode);
            $node->setAttribute(PhpDocAttributeKey::RESOLVED_CLASS, $className);

            return $node;
        });
    }

    private function resolveFullyQualifiedClass(ConstFetchNode $constFetchNode, PhpNode $phpNode): string
    {
        $nameScope = $this->nameScopeFactory->createNameScopeFromNodeWithoutTemplateTypes($phpNode);
        return $nameScope->resolveStringName($constFetchNode->className);
    }
}
