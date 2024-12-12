<?php

declare(strict_types=1);

namespace Rector\PHPStanStaticTypeMapper\TypeMapper;

use PhpParser\Node;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name\FullyQualified;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\Type\Accessory\HasMethodType;
use PHPStan\Type\Accessory\HasPropertyType;
use PHPStan\Type\ObjectWithoutClassType;
use PHPStan\Type\Type;
use Rector\NodeTypeResolver\PHPStan\ObjectWithoutClassTypeWithParentTypes;
use Rector\Php\PhpVersionProvider;
use Rector\PHPStanStaticTypeMapper\Contract\TypeMapperInterface;
use Rector\ValueObject\PhpVersionFeature;

/**
 * @implements TypeMapperInterface<ObjectWithoutClassType>
 */
final readonly class ObjectWithoutClassTypeMapper implements TypeMapperInterface
{
    public function __construct(
        private PhpVersionProvider $phpVersionProvider
    ) {
    }

    public function getNodeClass(): string
    {
        return ObjectWithoutClassType::class;
    }

    /**
     * @param ObjectWithoutClassType $type
     */
    public function mapToPHPStanPhpDocTypeNode(Type $type): TypeNode
    {
        return $type->toPhpDocNode();
    }

    /**
     * @param ObjectWithoutClassType|HasMethodType|HasPropertyType $type
     */
    public function mapToPhpParserNode(Type $type, string $typeKind): ?Node
    {
        // special case for anonymous classes that implement another type
        if ($type instanceof ObjectWithoutClassTypeWithParentTypes) {
            $parentTypes = $type->getParentTypes();
            if (count($parentTypes) === 1) {
                $parentType = $parentTypes[0];
                return new FullyQualified($parentType->getClassName());
            }
        }

        if (! $this->phpVersionProvider->isAtLeastPhpVersion(PhpVersionFeature::OBJECT_TYPE)) {
            return null;
        }

        return new Identifier('object');
    }
}
