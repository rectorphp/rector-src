<?php

declare(strict_types=1);

namespace Rector\StaticTypeMapper\PhpDocParser;

use PhpParser\Node;
use PHPStan\Analyser\NameScope;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\PhpDocParser\Ast\Type\UnionTypeNode;
use PHPStan\Type\Type;
use Rector\NodeTypeResolver\PHPStan\Type\TypeFactory;
use Rector\StaticTypeMapper\Contract\PhpDocParser\PhpDocTypeMapperInterface;
use Rector\StaticTypeMapper\PhpDoc\PhpDocTypeMapper;
use Symfony\Contracts\Service\Attribute\Required;

/**
 * @implements PhpDocTypeMapperInterface<UnionTypeNode>
 */
final class UnionTypeMapper implements PhpDocTypeMapperInterface
{
    private PhpDocTypeMapper $phpDocTypeMapper;

    public function __construct(
        private TypeFactory $typeFactory
    ) {
    }

    public function getNodeType(): string
    {
        return UnionTypeNode::class;
    }

    #[Required]
    public function autowireUnionTypeMapper(PhpDocTypeMapper $phpDocTypeMapper): void
    {
        $this->phpDocTypeMapper = $phpDocTypeMapper;
    }

    /**
     * @param UnionTypeNode $typeNode
     */
    public function mapToPHPStanType(TypeNode $typeNode, Node $node, NameScope $nameScope): Type
    {
        $unionedTypes = [];
        foreach ($typeNode->types as $unionedTypeNode) {
            $unionedTypes[] = $this->phpDocTypeMapper->mapToPHPStanType($unionedTypeNode, $node, $nameScope);
        }

        // to prevent missing class error, e.g. in tests
        return $this->typeFactory->createMixedPassedOrUnionTypeAndKeepConstant($unionedTypes);
    }
}
