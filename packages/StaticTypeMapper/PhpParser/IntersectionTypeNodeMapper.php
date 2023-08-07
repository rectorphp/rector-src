<?php

declare(strict_types=1);

namespace Rector\StaticTypeMapper\PhpParser;

use PhpParser\Node;
use PhpParser\Node\Identifier;
use PHPStan\Type\IntersectionType;
use PHPStan\Type\Type;
use Rector\StaticTypeMapper\Contract\PhpParser\PhpParserNodeMapperInterface;

/**
 * @implements PhpParserNodeMapperInterface<Node\IntersectionType>
 */
final class IntersectionTypeNodeMapper implements PhpParserNodeMapperInterface
{
    public function __construct(
        private readonly FullyQualifiedNodeMapper $fullyQualifiedNodeMapper,
        private readonly IdentifierNodeMapper $identifierNodeMapper
    )
    {
    }

    public function getNodeType(): string
    {
        return Node\IntersectionType::class;
    }

    /**
     * @param Node\IntersectionType $node
     */
    public function mapToPHPStan(Node $node): Type
    {
        $types = [];
        foreach ($node->types as $intersectionedType) {
            $types[] = $intersectionedType instanceof Identifier
                ? $this->identifierNodeMapper->mapToPHPStan($intersectionedType)
                : $this->fullyQualifiedNodeMapper->mapToPHPStan($intersectionedType);
        }

        return new IntersectionType($types);
    }
}
