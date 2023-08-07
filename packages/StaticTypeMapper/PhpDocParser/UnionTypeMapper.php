<?php

declare(strict_types=1);

namespace Rector\StaticTypeMapper\PhpDocParser;

use PhpParser\Node;
use PHPStan\Analyser\NameScope;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\PhpDocParser\Ast\Type\UnionTypeNode;
use PHPStan\Type\Type;
use Rector\NodeTypeResolver\PHPStan\Type\TypeFactory;
use Rector\StaticTypeMapper\Contract\PhpDocParser\PhpDocTypeMapperInterface;
use PHPStan\PhpDoc\TypeNodeResolver;
use PHPStan\PhpDocParser\Ast\Type\IntersectionTypeNode;

/**
 * @implements PhpDocTypeMapperInterface<UnionTypeNode>
 */
final class UnionTypeMapper implements PhpDocTypeMapperInterface
{
    public function __construct(
        private readonly TypeFactory $typeFactory,
        private readonly IdentifierTypeMapper $identifierTypeMapper,
        private readonly IntersectionTypeMapper $intersectionTypeMapper,
        private readonly TypeNodeResolver $typeNodeResolver
    ) {
    }

    public function getNodeType(): string
    {
        return UnionTypeNode::class;
    }

    /**
     * @param UnionTypeNode $typeNode
     */
    public function mapToPHPStanType(TypeNode $typeNode, Node $node, NameScope $nameScope): Type
    {
        $unionedTypes = [];
        foreach ($typeNode->types as $unionedTypeNode) {
            if ($unionedTypeNode instanceof IdentifierTypeNode) {
                $unionedTypes[] = $this->identifierTypeMapper->mapToPHPStanType($unionedTypeNode, $node, $nameScope);
                continue;
            }

            if ($unionedTypeNode instanceof IntersectionTypeNode) {
                $unionedTypes[] = $this->intersectionTypeMapper->mapToPHPStanType($unionedTypeNode, $node, $nameScope);
                continue;
            }

            $unionedTypes[] = $this->typeNodeResolver->resolve($unionedTypeNode, $nameScope);
        }

        // to prevent missing class error, e.g. in tests
        return $this->typeFactory->createMixedPassedOrUnionTypeAndKeepConstant($unionedTypes);
    }
}
