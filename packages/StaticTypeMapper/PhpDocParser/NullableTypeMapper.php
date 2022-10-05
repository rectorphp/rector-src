<?php

declare(strict_types=1);

namespace Rector\StaticTypeMapper\PhpDocParser;

use PhpParser\Node;
use PHPStan\Analyser\NameScope;
use PHPStan\PhpDoc\TypeNodeResolver;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\PhpDocParser\Ast\Type\NullableTypeNode;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\Type\NullType;
use PHPStan\Type\Type;
use PHPStan\Type\UnionType;
use Rector\StaticTypeMapper\Contract\PhpDocParser\PhpDocTypeMapperInterface;

/**
 * @implements PhpDocTypeMapperInterface<NullableTypeNode>
 */
final class NullableTypeMapper implements PhpDocTypeMapperInterface
{
    public function __construct(
        private readonly IdentifierTypeMapper $identifierTypeMapper,
        private readonly TypeNodeResolver $typeNodeResolver
    ) {
    }

    public function getNodeType(): string
    {
        return NullableTypeNode::class;
    }

    /**
     * @param NullableTypeNode $typeNode
     */
    public function mapToPHPStanType(TypeNode $typeNode, Node $node, NameScope $nameScope): Type
    {
        if ($typeNode->type instanceof IdentifierTypeNode) {
            $type = $this->identifierTypeMapper->mapToPHPStanType($typeNode->type, $node, $nameScope);

            if ($type instanceof UnionType) {
                return new UnionType([new NullType(), ...$type->getTypes()]);
            }

            return new UnionType([new NullType(), $type]);
        }

        // fallback to PHPStan resolver
        return $this->typeNodeResolver->resolve($typeNode, $nameScope);
    }
}
