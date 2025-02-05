<?php

declare(strict_types=1);

namespace Rector\PHPStanStaticTypeMapper\TypeMapper;

use PhpParser\Node;
use PhpParser\Node\Name\FullyQualified;
use PHPStan\PhpDocParser\Ast\Node as AstNode;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\Type\ClosureType;
use PHPStan\Type\Type;
use Rector\BetterPhpDocParser\ValueObject\Type\FullyQualifiedIdentifierTypeNode;
use Rector\Php\PhpVersionProvider;
use Rector\PhpDocParser\PhpDocParser\PhpDocNodeTraverser;
use Rector\PHPStanStaticTypeMapper\Contract\TypeMapperInterface;
use Rector\PHPStanStaticTypeMapper\Enum\TypeKind;
use Rector\ValueObject\PhpVersionFeature;

/**
 * @implements TypeMapperInterface<ClosureType>
 */
final readonly class ClosureTypeMapper implements TypeMapperInterface
{
    public function __construct(
        private PhpVersionProvider $phpVersionProvider
    ) {
    }

    public function getNodeClass(): string
    {
        return ClosureType::class;
    }

    /**
     * @param ClosureType $type
     */
    public function mapToPHPStanPhpDocTypeNode(Type $type): TypeNode
    {
        $typeNode = $type->toPhpDocNode();

        $phpDocNodeTraverser = new PhpDocNodeTraverser();
        $phpDocNodeTraverser->traverseWithCallable(
            $typeNode,
            '',
            static function (AstNode $astNode): ?FullyQualifiedIdentifierTypeNode {
                if (! $astNode instanceof IdentifierTypeNode) {
                    return null;
                }

                if ($astNode->name !== 'Closure') {
                    return null;
                }

                return new FullyQualifiedIdentifierTypeNode('Closure');
            }
        );

        return $typeNode;
    }

    /**
     * @param TypeKind::* $typeKind
     * @param ClosureType $type
     */
    public function mapToPhpParserNode(Type $type, string $typeKind): ?Node
    {
        // ref https://3v4l.org/iKMK6#v5.3.29
        if ($typeKind === TypeKind::PARAM && $this->phpVersionProvider->isAtLeastPhpVersion(
            PhpVersionFeature::ANONYMOUS_FUNCTION_PARAM_TYPE
        )) {
            return new FullyQualified('Closure');
        }

        // ref https://3v4l.org/g8WvW#v7.4.0
        if ($typeKind === TypeKind::PROPERTY && $this->phpVersionProvider->isAtLeastPhpVersion(
            PhpVersionFeature::TYPED_PROPERTIES
        )) {
            return new FullyQualified('Closure');
        }

        // ref https://3v4l.org/nUreN#v7.0.0
        if ($typeKind === TypeKind::RETURN && $this->phpVersionProvider->isAtLeastPhpVersion(
            PhpVersionFeature::ANONYMOUS_FUNCTION_RETURN_TYPE
        )) {
            return new FullyQualified('Closure');
        }

        // ref https://3v4l.org/ruh5g#v8.0.0
        if ($typeKind === TypeKind::UNION && $this->phpVersionProvider->isAtLeastPhpVersion(
            PhpVersionFeature::UNION_TYPES
        )) {
            return new FullyQualified('Closure');
        }

        return null;
    }
}
