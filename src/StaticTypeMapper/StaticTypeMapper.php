<?php

declare(strict_types=1);

namespace Rector\StaticTypeMapper;

use PhpParser\Node;
use PhpParser\Node\ComplexType;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PHPStan\Php\PhpVersion;
use PHPStan\PhpDocParser\Ast\PhpDoc\ParamTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\ReturnTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\TemplateTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\ThrowsTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\VarTagValueNode;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\Type\BooleanType;
use PHPStan\Type\MixedType;
use PHPStan\Type\NativeTypePosition;
use PHPStan\Type\Type;
use PHPStan\Type\TypeTraverser;
use Rector\Exception\NotImplementedYetException;
use Rector\Php\PhpVersionProvider;
use Rector\PHPStanStaticTypeMapper\Enum\TypeKind;
use Rector\PHPStanStaticTypeMapper\PHPStanStaticTypeMapper;
use Rector\StaticTypeMapper\Mapper\PhpParserNodeMapper;
use Rector\StaticTypeMapper\Naming\NameScopeFactory;
use Rector\StaticTypeMapper\PhpDoc\PhpDocTypeMapper;

/**
 * Maps PhpParser <=> PHPStan <=> PHPStan doc <=> string type nodes between all possible formats
 * @see \Rector\Tests\NodeTypeResolver\StaticTypeMapper\StaticTypeMapperTest
 */
final readonly class StaticTypeMapper
{
    public function __construct(
        private NameScopeFactory $nameScopeFactory,
        private PHPStanStaticTypeMapper $phpStanStaticTypeMapper,
        private PhpDocTypeMapper $phpDocTypeMapper,
        private PhpParserNodeMapper $phpParserNodeMapper,
        private PhpVersionProvider $phpVersionProvider
    ) {
    }

    public function mapPHPStanTypeToPHPStanPhpDocTypeNode(Type $phpStanType): TypeNode
    {
        return $this->phpStanStaticTypeMapper->mapToPHPStanPhpDocTypeNode($phpStanType);
    }

    /**
     * @param TypeKind::* $typeKind
     */
    public function mapPHPStanTypeToPhpParserNode(Type $phpStanType, string $typeKind): Name|ComplexType|Identifier|null
    {
        if ($typeKind === TypeKind::PARAM) {
            return $phpStanType->toNativePhpTypeNode(NativeTypePosition::parameter($this->getPhpVersion()));
        }

        if ($typeKind === TypeKind::RETURN) {
            return $phpStanType->toNativePhpTypeNode(NativeTypePosition::returnType($this->getPhpVersion()));
        }

        // Rector policy: a property or constant holding only true, false or null is too narrow to be useful
        if ($phpStanType->isNull()->yes()) {
            return null;
        }

        $phpStanType = TypeTraverser::map($phpStanType, static function (Type $type, callable $traverse): Type {
            if ($type->isTrue()->yes() || $type->isFalse()->yes()) {
                return new BooleanType();
            }

            return $traverse($type);
        });

        return $phpStanType->toNativePhpTypeNode(NativeTypePosition::property($this->getPhpVersion()));
    }

    public function mapPhpParserNodePHPStanType(Node $node): Type
    {
        return $this->phpParserNodeMapper->mapToPHPStanType($node);
    }

    public function mapPHPStanPhpDocTypeToPHPStanType(PhpDocTagValueNode $phpDocTagValueNode, Node $node): Type
    {
        if ($phpDocTagValueNode instanceof TemplateTagValueNode) {
            // special case
            if (! $phpDocTagValueNode->bound instanceof TypeNode) {
                return new MixedType();
            }

            $nameScope = $this->nameScopeFactory->createNameScopeFromNodeWithoutTemplateTypes($node);
            return $this->phpDocTypeMapper->mapToPHPStanType($phpDocTagValueNode->bound, $node, $nameScope);
        }

        if ($phpDocTagValueNode instanceof ReturnTagValueNode || $phpDocTagValueNode instanceof ParamTagValueNode || $phpDocTagValueNode instanceof VarTagValueNode || $phpDocTagValueNode instanceof ThrowsTagValueNode) {
            return $this->mapPHPStanPhpDocTypeNodeToPHPStanType($phpDocTagValueNode->type, $node);
        }

        throw new NotImplementedYetException(__METHOD__ . ' for ' . $phpDocTagValueNode::class);
    }

    public function mapPHPStanPhpDocTypeNodeToPHPStanType(TypeNode $typeNode, Node $node): Type
    {
        $nameScope = $this->nameScopeFactory->createNameScopeFromNodeWithoutTemplateTypes($node);
        return $this->phpDocTypeMapper->mapToPHPStanType($typeNode, $node, $nameScope);
    }

    private function getPhpVersion(): PhpVersion
    {
        return new PhpVersion($this->phpVersionProvider->provide());
    }
}
