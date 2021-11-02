<?php

declare(strict_types=1);

namespace Rector\StaticTypeMapper\PhpParser;

use PhpParser\Node;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Param;
use PHPStan\Type\Type;
use Rector\CodingStyle\ClassNameImport\UsedImportsResolver;
use Rector\Core\Provider\CurrentFileProvider;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\StaticTypeMapper\Contract\PhpParser\PhpParserNodeMapperInterface;
use Rector\StaticTypeMapper\ValueObject\Type\AliasedObjectType;
use Rector\StaticTypeMapper\ValueObject\Type\FullyQualifiedObjectType;

final class FullyQualifiedNodeMapper implements PhpParserNodeMapperInterface
{
    public function __construct(private CurrentFileProvider $currentFileProvider, private UsedImportsResolver $usedImportsResolver)
    {
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
        $parent = $node->getAttribute(AttributeKey::PARENT_NODE);
        if ($parent instanceof Param) {
            $file = $this->currentFileProvider->getFile();
            $oldTokens = $file->getOldTokens();
            $startTokenPos = $node->getStartTokenPos();

            $type = $oldTokens[$startTokenPos][1];
            if (! str_contains($type, '\\')) {
                $objectTypes = $this->usedImportsResolver->resolveForNode($node);
                foreach ($objectTypes as $objectType) {
                    if ($objectType instanceof AliasedObjectType && $objectType->getClassName() === $type) {
                        return $objectType;
                    }
                }
            }
        }

        $originalName = (string) $node->getAttribute(AttributeKey::ORIGINAL_NAME);
        $fullyQualifiedName = $node->toString();

        // is aliased?
        if ($this->isAliasedName($originalName, $fullyQualifiedName) && $originalName !== $fullyQualifiedName
        ) {
            return new AliasedObjectType($originalName, $fullyQualifiedName);
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
