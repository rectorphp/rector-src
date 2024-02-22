<?php

declare(strict_types=1);

namespace Rector\PHPStanStaticTypeMapper\TypeMapper;

use PhpParser\Node;
use PhpParser\Node\Name\FullyQualified;
use PHPStan\PhpDocParser\Ast\Node as AstNode;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\Type\IntersectionType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\ObjectWithoutClassType;
use PHPStan\Type\Type;
use Rector\Php\PhpVersionProvider;
use Rector\PhpDocParser\PhpDocParser\PhpDocNodeTraverser;
use Rector\PHPStanStaticTypeMapper\Contract\TypeMapperInterface;
use Rector\ValueObject\PhpVersionFeature;

/**
 * @implements TypeMapperInterface<IntersectionType>
 */
final readonly class IntersectionTypeMapper implements TypeMapperInterface
{
    public function __construct(
        private PhpVersionProvider $phpVersionProvider,
        private ObjectWithoutClassTypeMapper $objectWithoutClassTypeMapper,
        private ObjectTypeMapper $objectTypeMapper,
    ) {
    }

    public function getNodeClass(): string
    {
        return IntersectionType::class;
    }

    /**
     * @param IntersectionType $type
     */
    public function mapToPHPStanPhpDocTypeNode(Type $type): TypeNode
    {
        $typeNode = $type->toPhpDocNode();

        $phpDocNodeTraverser = new PhpDocNodeTraverser();
        $phpDocNodeTraverser->traverseWithCallable(
            $typeNode,
            '',
            static function (AstNode $astNode): ?IdentifierTypeNode {
                if ($astNode instanceof IdentifierTypeNode) {
                    $astNode->name = '\\' . ltrim($astNode->name, '\\');
                    return $astNode;
                }

                return null;
            }
        );

        return $typeNode;
    }

    /**
     * @param IntersectionType $type
     */
    public function mapToPhpParserNode(Type $type, string $typeKind): ?Node
    {
        if (! $this->phpVersionProvider->isAtLeastPhpVersion(PhpVersionFeature::INTERSECTION_TYPES)) {
            return null;
        }

        $intersectionedTypeNodes = [];
        foreach ($type->getTypes() as $type) {
            if ($type instanceof ObjectWithoutClassType) {
                return $this->objectWithoutClassTypeMapper->mapToPhpParserNode($type, $typeKind);
            }

            if (! $type instanceof ObjectType) {
                return null;
            }

            $resolvedType = $this->objectTypeMapper->mapToPhpParserNode($type, $typeKind);

            if (! $resolvedType instanceof FullyQualified) {
                return null;
            }

            $intersectionedTypeNodes[] = $resolvedType;
        }

        if ($intersectionedTypeNodes === []) {
            return null;
        }

        if (count($intersectionedTypeNodes) === 1) {
            return current($intersectionedTypeNodes);
        }

        return new Node\IntersectionType($intersectionedTypeNodes);
    }
}
