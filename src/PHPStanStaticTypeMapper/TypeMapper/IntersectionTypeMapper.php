<?php

declare(strict_types=1);

namespace Rector\PHPStanStaticTypeMapper\TypeMapper;

use PHPStan\PhpDocParser\Ast\Node as AstNode;
use PHPStan\PhpDocParser\Ast\NodeTraverser;
use PHPStan\PhpDocParser\Ast\Type\ArrayShapeItemNode;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\PhpDocParser\Ast\Type\UnionTypeNode;
use PHPStan\Type\IntersectionType;
use PHPStan\Type\MixedType;
use PHPStan\Type\Type;
use Rector\PhpDocParser\PhpDocParser\PhpDocNodeVisitor\CallablePhpDocNodeVisitor;
use Rector\PHPStanStaticTypeMapper\Contract\TypeMapperInterface;
use Rector\StaticTypeMapper\Mapper\ScalarStringToTypeMapper;

/**
 * @implements TypeMapperInterface<IntersectionType>
 */
final readonly class IntersectionTypeMapper implements TypeMapperInterface
{
    public function __construct(
        private ScalarStringToTypeMapper $scalarStringToTypeMapper,
    ) {
    }

    /**
     * @return array<class-string<Type>>
     */
    public function getNodeClasses(): array
    {
        return [IntersectionType::class];
    }

    /**
     * @param IntersectionType $type
     */
    public function mapToPHPStanPhpDocTypeNode(Type $type): TypeNode
    {
        $typeNode = $type->toPhpDocNode();

        $phpDocNodeTraverser = new NodeTraverser([new CallablePhpDocNodeVisitor(
            function (AstNode $astNode) use ($typeNode): int|IdentifierTypeNode|null {
                if ($astNode === $typeNode) {
                    return null;
                }

                if ($astNode instanceof UnionTypeNode) {
                    return NodeTraverser::DONT_TRAVERSE_CURRENT_AND_CHILDREN;
                }

                if ($astNode instanceof ArrayShapeItemNode) {
                    return NodeTraverser::DONT_TRAVERSE_CURRENT_AND_CHILDREN;
                }

                if (! $astNode instanceof IdentifierTypeNode) {
                    return NodeTraverser::DONT_TRAVERSE_CURRENT_AND_CHILDREN;
                }

                $type = $this->scalarStringToTypeMapper->mapScalarStringToType($astNode->name);
                if ($type->isScalar()->yes()) {
                    return NodeTraverser::DONT_TRAVERSE_CURRENT_AND_CHILDREN;
                }

                if ($type->isArray()->yes()) {
                    return NodeTraverser::DONT_TRAVERSE_CURRENT_AND_CHILDREN;
                }

                if ($type instanceof MixedType && $type->isExplicitMixed()) {
                    return NodeTraverser::DONT_TRAVERSE_CURRENT_AND_CHILDREN;
                }

                $astNode->name = '\\' . ltrim($astNode->name, '\\');
                return $astNode;
            }
        )]);
        $phpDocNodeTraverser->traverse([$typeNode]);

        return $typeNode;
    }
}
