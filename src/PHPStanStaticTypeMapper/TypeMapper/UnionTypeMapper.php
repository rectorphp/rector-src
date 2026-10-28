<?php

declare(strict_types=1);

namespace Rector\PHPStanStaticTypeMapper\TypeMapper;

use PHPStan\PhpDocParser\Ast\Type\UnionTypeNode;
use PHPStan\Type\ArrayType;
use PHPStan\Type\MixedType;
use PHPStan\Type\NeverType;
use PHPStan\Type\Type;
use PHPStan\Type\UnionType;
use Rector\PHPStanStaticTypeMapper\Contract\TypeMapperInterface;
use Rector\PHPStanStaticTypeMapper\PHPStanStaticTypeMapper;

/**
 * @implements TypeMapperInterface<UnionType>
 */
final class UnionTypeMapper implements TypeMapperInterface
{
    private PHPStanStaticTypeMapper $phpStanStaticTypeMapper;

    public function autowire(PHPStanStaticTypeMapper $phpStanStaticTypeMapper): void
    {
        $this->phpStanStaticTypeMapper = $phpStanStaticTypeMapper;
    }

    /**
     * @return array<class-string<Type>>
     */
    public function getNodeClasses(): array
    {
        return [UnionType::class];
    }

    /**
     * @param UnionType $type
     */
    public function mapToPHPStanPhpDocTypeNode(Type $type): BracketsAwareUnionTypeNode
    {
        $unionTypesNodes = [];

        foreach ($type->getTypes() as $unionedType) {
            if ($unionedType instanceof ArrayType && $unionedType->getItemType() instanceof NeverType) {
                $unionedType = new ArrayType($unionedType->getKeyType(), new MixedType());
            }

            $unionedTypeNode = $this->phpStanStaticTypeMapper->mapToPHPStanPhpDocTypeNode($unionedType);

            // an array of union item type is mapped to "A[]|B[]", merge it into this union
            $typeNodes = $unionedTypeNode instanceof UnionTypeNode ? $unionedTypeNode->types : [$unionedTypeNode];

            // remove duplicates, e.g. "int[]|int[]"
            foreach ($typeNodes as $typeNode) {
                $unionTypesNodes[(string) $typeNode] = $typeNode;
            }
        }

        return new UnionTypeNode(array_values($unionTypesNodes));
    }
}
