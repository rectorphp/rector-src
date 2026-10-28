<?php

declare(strict_types=1);

namespace Rector\PHPStanStaticTypeMapper\TypeMapper;

use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\Type\ArrayType;
use PHPStan\Type\Generic\GenericObjectType;
use PHPStan\Type\MixedType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use PHPStan\Type\TypeTraverser;
use Rector\PHPStanStaticTypeMapper\Contract\TypeMapperInterface;
use Rector\StaticTypeMapper\ValueObject\Type\FullyQualifiedObjectType;

/**
 * @implements TypeMapperInterface<ObjectType>
 */
final class ObjectTypeMapper implements TypeMapperInterface
{
    /**
     * @return array<class-string<Type>>
     */
    public function getNodeClasses(): array
    {
        return [ObjectType::class];
    }

    /**
     * @param ObjectType $type
     */
    public function mapToPHPStanPhpDocTypeNode(Type $type): TypeNode
    {
        $type = TypeTraverser::map($type, static function (Type $type, callable $traverse): Type {
            if ($type instanceof ArrayType && ($type->getItemType() instanceof MixedType && $type->getKeyType() instanceof MixedType)) {
                return new ArrayType(new MixedType(), new MixedType(true));
            }

            if (! $type instanceof ObjectType) {
                return $traverse($type);
            }

            $typeClass = $type::class;

            // early native ObjectType check
            if ($typeClass === 'PHPStan\Type\ObjectType') {
                return new ObjectType('\\' . $type->getClassName());
            }

            if ($type instanceof FullyQualifiedObjectType) {
                return new ObjectType('\\' . $type->getClassName());
            }

            if ($type instanceof GenericObjectType) {
                return $traverse(new GenericObjectType('\\' . $type->getClassName(), $type->getTypes()));
            }

            return $traverse($type);
        });

        return $type->toPhpDocNode();
    }
}
