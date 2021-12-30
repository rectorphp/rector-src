<?php

declare(strict_types=1);

namespace Rector\StaticTypeMapper\PhpDocParser;

use PhpParser\Node;
use PHPStan\Analyser\NameScope;
use PHPStan\PhpDoc\TypeNodeResolver;
use PHPStan\PhpDocParser\Ast\Type\GenericTypeNode;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\Type\Constant\ConstantArrayType;
use PHPStan\Type\Type;
use Rector\Core\Exception\NotImplementedYetException;
use Rector\StaticTypeMapper\Contract\PhpDocParser\PhpDocTypeMapperInterface;
use Rector\StaticTypeMapper\PhpDoc\PhpDocTypeMapper;
use Symfony\Contracts\Service\Attribute\Required;

/**
 * @implements PhpDocTypeMapperInterface<GenericTypeNode>
 */
final class GenericTypeMapper implements PhpDocTypeMapperInterface
{
    private PhpDocTypeMapper $phpDocTypeMapper;

    public function __construct(
        private readonly IdentifierTypeMapper $identifierTypeMapper,
        private readonly TypeNodeResolver $typeNodeResolver
    ) {
    }

    #[Required]
    public function autowire(PhpDocTypeMapper $phpDocTypeMapper): void
    {
        $this->phpDocTypeMapper = $phpDocTypeMapper;
    }

    public function getNodeType(): string
    {
        return GenericTypeNode::class;
    }

    /**
     * @param GenericTypeNode $typeNode
     */
    public function mapToPHPStanType(TypeNode $typeNode, Node $node, NameScope $nameScope): Type
    {
        $mainTypeNode = $typeNode->type;
        if ($mainTypeNode->name === 'array') {
            $genericTypes = [];
            foreach ($typeNode->genericTypes as $genericTypeNode) {
                $genericTypes[] = $this->phpDocTypeMapper->mapToPHPStanType($genericTypeNode, $node, $nameScope);
            }

            return new ConstantArrayType([], $genericTypes);
        }

        throw new NotImplementedYetException(get_class($typeNode));
    }
}
