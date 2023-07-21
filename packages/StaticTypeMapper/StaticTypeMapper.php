<?php

declare(strict_types=1);

namespace Rector\StaticTypeMapper;

use PhpParser\Node;
use PhpParser\Node\ComplexType;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\UnionType;
use PHPStan\PhpDocParser\Ast\PhpDoc\ParamTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\ReturnTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\TemplateTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\ThrowsTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\VarTagValueNode;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\Type\MixedType;
use PHPStan\Type\Type;
use Rector\Core\Exception\NotImplementedYetException;
use Rector\NodeNameResolver\NodeNameResolver;
use Rector\PHPStanStaticTypeMapper\Enum\TypeKind;
use Rector\PHPStanStaticTypeMapper\PHPStanStaticTypeMapper;
use Rector\StaticTypeMapper\Mapper\PhpParserNodeMapper;
use Rector\StaticTypeMapper\Naming\NameScopeFactory;
use Rector\StaticTypeMapper\PhpDoc\PhpDocTypeMapper;
use Rector\StaticTypeMapper\PhpDocParser\IdentifierTypeMapper;

/**
 * Maps PhpParser <=> PHPStan <=> PHPStan doc <=> string type nodes between all possible formats
 * @see \Rector\Tests\NodeTypeResolver\StaticTypeMapper\StaticTypeMapperTest
 */
final class StaticTypeMapper
{
    /**
     * @var array<string, string>
     */
    private const STANDALONE_MAPS = [
        'false' => 'bool',
    ];

    public function __construct(
        private readonly NameScopeFactory $nameScopeFactory,
        private readonly PHPStanStaticTypeMapper $phpStanStaticTypeMapper,
        private readonly PhpDocTypeMapper $phpDocTypeMapper,
        private readonly PhpParserNodeMapper $phpParserNodeMapper,
        private readonly NodeNameResolver $nodeNameResolver,
        private readonly IdentifierTypeMapper $identifierTypeMapper,
    ) {
    }

    public function mapPHPStanTypeToPHPStanPhpDocTypeNode(Type $phpStanType): TypeNode
    {
        return $this->phpStanStaticTypeMapper->mapToPHPStanPhpDocTypeNode($phpStanType);
    }

    /**
     * @param TypeKind::* $typeKind
     * @return Name|ComplexType|Identifier|null
     */
    public function mapPHPStanTypeToPhpParserNode(Type $phpStanType, string $typeKind): ?Node
    {
        $node = $this->phpStanStaticTypeMapper->mapToPhpParserNode($phpStanType, $typeKind);
        if (! $node instanceof Node) {
            return null;
        }

        if ($node instanceof UnionType) {
            return $node;
        }

        if (! $node instanceof Name) {
            return $node;
        }

        $nodeName = $this->nodeNameResolver->getName($node);

        foreach (self::STANDALONE_MAPS as $key => $type) {
            if ($nodeName === $key) {
                return new Name($type);
            }
        }

        return $node;
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
        $nameScope = $this->nameScopeFactory->createNameScopeFromNode($node);
        return $this->phpDocTypeMapper->mapToPHPStanType($typeNode, $node, $nameScope);
    }

    public function mapIdentifierTypeNodeToPHPStanType(IdentifierTypeNode $typeNode, Node $node): Type
    {
        return $this->identifierTypeMapper->mapToPHPStanTypeNew($typeNode, $node);
    }
}
