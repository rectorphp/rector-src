<?php

declare(strict_types=1);

namespace Rector\StaticTypeMapper;

use PhpParser\Node;
use PhpParser\Node\ComplexType;
use PhpParser\Node\Name;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\PhpDocParser\Ast\PhpDoc\ParamTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\ReturnTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\TemplateTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\ThrowsTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\VarTagValueNode;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\Type\MixedType;
use PHPStan\Type\Type;
use Rector\Core\Exception\NotImplementedYetException;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\PHPStanStaticTypeMapper\Enum\TypeKind;
use Rector\PHPStanStaticTypeMapper\PHPStanStaticTypeMapper;
use Rector\StaticTypeMapper\Mapper\PhpParserNodeMapper;
use Rector\StaticTypeMapper\Naming\NameScopeFactory;
use Rector\StaticTypeMapper\PhpDoc\PhpDocTypeMapper;

/**
 * Maps PhpParser <=> PHPStan <=> PHPStan doc <=> string type nodes between all possible formats
 * @see \Rector\Tests\NodeTypeResolver\StaticTypeMapper\StaticTypeMapperTest
 */
final class StaticTypeMapper
{
    public function __construct(
        private NameScopeFactory $nameScopeFactory,
        private PHPStanStaticTypeMapper $phpStanStaticTypeMapper,
        private PhpDocTypeMapper $phpDocTypeMapper,
        private PhpParserNodeMapper $phpParserNodeMapper,
    ) {
    }

    public function mapPHPStanTypeToPHPStanPhpDocTypeNode(Type $phpStanType, TypeKind $typeKind): TypeNode
    {
        return $this->phpStanStaticTypeMapper->mapToPHPStanPhpDocTypeNode($phpStanType, $typeKind);
    }

    /**
     * @return Name|ComplexType|null
     */
    public function mapPHPStanTypeToPhpParserNode(Type $phpStanType, TypeKind $typeKind): ?Node
    {
        return $this->phpStanStaticTypeMapper->mapToPhpParserNode($phpStanType, $typeKind);
    }

    public function mapPhpParserNodePHPStanType(Node $node): Type
    {
        return $this->phpParserNodeMapper->mapToPHPStanType($node);
    }

    public function mapPHPStanPhpDocTypeToPHPStanType(PhpDocTagValueNode $phpDocTagValueNode, Node $node): Type
    {
        if ($phpDocTagValueNode instanceof TemplateTagValueNode) {
            // special case
            $nameScope = $this->nameScopeFactory->createNameScopeFromNodeWithoutTemplateTypes($node);
            if ($phpDocTagValueNode->bound === null) {
                return new MixedType();
            }

            return $this->phpDocTypeMapper->mapToPHPStanType($phpDocTagValueNode->bound, $node, $nameScope);
        }

        if ($phpDocTagValueNode instanceof ReturnTagValueNode || $phpDocTagValueNode instanceof ParamTagValueNode || $phpDocTagValueNode instanceof VarTagValueNode || $phpDocTagValueNode instanceof ThrowsTagValueNode) {
            return $this->mapPHPStanPhpDocTypeNodeToPHPStanType($phpDocTagValueNode->type, $node);
        }

        throw new NotImplementedYetException(__METHOD__ . ' for ' . $phpDocTagValueNode::class);
    }

    public function mapPHPStanPhpDocTypeNodeToPHPStanType(TypeNode $typeNode, Node $node): Type
    {
        if ($node instanceof Param) {
            $classMethod = $node->getAttribute(AttributeKey::PARENT_NODE);
            if ($classMethod instanceof ClassMethod) {
                // param does not hany any clue about template map, but class method has
                $node = $classMethod;
            }
        }

        $nameScope = $this->nameScopeFactory->createNameScopeFromNode($node);

        return $this->phpDocTypeMapper->mapToPHPStanType($typeNode, $node, $nameScope);
    }
}
