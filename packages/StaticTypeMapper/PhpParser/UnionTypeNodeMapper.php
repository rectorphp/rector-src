<?php

declare(strict_types=1);

namespace Rector\StaticTypeMapper\PhpParser;

use PhpParser\Node;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\UnionType;
use PHPStan\Type\Type;
use Rector\NodeTypeResolver\PHPStan\Type\TypeFactory;
use Rector\StaticTypeMapper\Contract\PhpParser\PhpParserNodeMapperInterface;

/**
 * @implements PhpParserNodeMapperInterface<UnionType>
 */
final class UnionTypeNodeMapper implements PhpParserNodeMapperInterface
{
    public function __construct(
        private readonly TypeFactory $typeFactory,
        private readonly FullyQualifiedNodeMapper $fullyQualifiedNodeMapper,
        private readonly NameNodeMapper $nameNodeMapper,
        private readonly IdentifierNodeMapper $identifierTypeMapper,
        private readonly IntersectionTypeNodeMapper $intersectionTypeMapper
    ) {
    }

    public function getNodeType(): string
    {
        return UnionType::class;
    }

    /**
     * @param UnionType $node
     */
    public function mapToPHPStan(Node $node): Type
    {
        $types = [];
        foreach ($node->types as $unionedType) {
            if ($unionedType instanceof FullyQualified) {
                $types[] = $this->fullyQualifiedNodeMapper->mapToPHPStan($unionedType);
                continue;
            }

            if ($unionedType instanceof Name) {
                $types[] = $this->nameNodeMapper->mapToPHPStan($unionedType);
                continue;
            }

            if ($unionedType instanceof Identifier) {
                $types[] = $this->identifierTypeMapper->mapToPHPStan($unionedType);
                continue;
            }

            $types[] = $this->intersectionTypeMapper->mapToPHPStan($unionedType);
        }

        return $this->typeFactory->createMixedPassedOrUnionType($types, true);
    }
}
