<?php

declare(strict_types=1);

namespace Rector\StaticTypeMapper\PhpDocParser;

use PhpParser\Node;
use PHPStan\Analyser\NameScope;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\PhpDocParser\Ast\Type\NullableTypeNode;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\Type\NullType;
use PHPStan\Type\Type;
use PHPStan\Type\UnionType;
use Rector\Core\Exception\ShouldNotHappenException;
use Rector\StaticTypeMapper\Contract\PhpDocParser\PhpDocTypeMapperInterface;

final class NullableTypeMapper implements PhpDocTypeMapperInterface
{
    public function __construct(
        private IdentifierTypeMapper $identifierTypeMapper
    ) {
    }

    /**
     * @return class-string<TypeNode>
     */
    public function getNodeType(): string
    {
        return NullableTypeNode::class;
    }

    /**
     * @param NullableTypeNode $typeNode
     */
    public function mapToPHPStanType(TypeNode $typeNode, Node $node, NameScope $nameScope): Type
    {
        $type = $typeNode->type;
        if (! $type instanceof IdentifierTypeNode) {
            throw new ShouldNotHappenException();
        }

        $type = $this->identifierTypeMapper->mapToPHPStanType($type, $node, $nameScope);
        return new UnionType([new NullType(), $type]);
    }
}
