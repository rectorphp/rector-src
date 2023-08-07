<?php

declare(strict_types=1);

namespace Rector\StaticTypeMapper\PhpParser;

use PhpParser\Node;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
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
        private readonly NameNodeMapper $nameNodeMapper,
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
            if ($intersectionedType instanceof FullyQualified) {
                $types[] = $this->fullyQualifiedNodeMapper->mapToPHPStan($intersectionedType);
                continue;
            }

            if ($intersectionedType instanceof Name) {
                $types[] = $this->nameNodeMapper->mapToPHPStan($intersectionedType);
                continue;
            }

            $types[] = $this->identifierNodeMapper->mapToPHPStan($intersectionedType);
        }

        return new IntersectionType($types);
    }
}
