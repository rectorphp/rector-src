<?php

declare(strict_types=1);

namespace Rector\StaticTypeMapper\PhpParser;

use PhpParser\Node;
use PhpParser\Node\Name\FullyQualified;
use PHPStan\Type\Type;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\NodeTypeResolver\NodeTypeResolver;
use Rector\StaticTypeMapper\Contract\PhpParser\PhpParserNodeMapperInterface;
use Rector\StaticTypeMapper\ValueObject\Type\AliasedObjectType;
use Rector\StaticTypeMapper\ValueObject\Type\FullyQualifiedObjectType;
use Symfony\Contracts\Service\Attribute\Required;

final class FullyQualifiedNodeMapper implements PhpParserNodeMapperInterface
{
    private NodeTypeResolver $nodeTypeResolver;

    #[Required]
    public function autowireFullyQualifiedNodeMapper(NodeTypeResolver $nodeTypeResolver): void
    {
        $this->nodeTypeResolver = $nodeTypeResolver;
    }

    /**
     * @return class-string<Node>
     */
    public function getNodeType(): string
    {
        return FullyQualified::class;
    }

    /**
     * @param FullyQualified $node
     */
    public function mapToPHPStan(Node $node): Type
    {
        $originalName = (string) $node->getAttribute(AttributeKey::ORIGINAL_NAME);
        $fullyQualifiedName = $node->toString();

        // is aliased?
        if ($this->isAliasedName($originalName, $fullyQualifiedName) && $originalName !== $fullyQualifiedName
        ) {
            return new AliasedObjectType($originalName, $fullyQualifiedName);
        }

        $possibleAliasedObjectType = $this->nodeTypeResolver->getType($node);
        if ($possibleAliasedObjectType instanceof AliasedObjectType) {
            return $possibleAliasedObjectType;
        }

        return new FullyQualifiedObjectType($fullyQualifiedName);
    }

    private function isAliasedName(string $originalName, string $fullyQualifiedName): bool
    {
        if ($originalName === '') {
            return false;
        }

        if ($originalName === $fullyQualifiedName) {
            return false;
        }

        return ! \str_ends_with($fullyQualifiedName, '\\' . $originalName);
    }
}
