<?php

declare(strict_types=1);

namespace Rector\StaticTypeMapper\PhpDocParser;

use PhpParser\Node;
use PHPStan\Analyser\NameScope;
use PHPStan\PhpDocParser\Ast\Type\NullableTypeNode;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\Type\NullType;
use PHPStan\Type\UnionType;
use PHPStan\Type\Type;

final class NullableTypeMapper extends IdentifierTypeMapper
{
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
        $type = parent::mapToPHPStanType($type, $node, $nameScope);

        return new UnionType([new NullType(), $type]);
    }
}
