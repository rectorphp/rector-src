<?php

declare(strict_types=1);

namespace Rector\PHPStanStaticTypeMapper\TypeMapper;

use Nette\Utils\Strings;
use PhpParser\Node;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\Type\Generic\GenericObjectType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use PHPStan\Type\TypeTraverser;
use Rector\PHPStanStaticTypeMapper\Contract\TypeMapperInterface;
use Rector\StaticTypeMapper\ValueObject\Type\AliasedObjectType;
use Rector\StaticTypeMapper\ValueObject\Type\FullyQualifiedObjectType;
use Rector\StaticTypeMapper\ValueObject\Type\NonExistingObjectType;
use Rector\StaticTypeMapper\ValueObject\Type\SelfObjectType;
use Rector\StaticTypeMapper\ValueObject\Type\ShortenedObjectType;

/**
 * @implements TypeMapperInterface<ObjectType>
 */
final class ObjectTypeMapper implements TypeMapperInterface
{
    /**
     * @return class-string<Type>
     */
    public function getNodeClass(): string
    {
        return ObjectType::class;
    }

    /**
     * @param ObjectType $type
     */
    public function mapToPHPStanPhpDocTypeNode(Type $type): TypeNode
    {
        $type = TypeTraverser::map($type, static function (Type $type, callable $traverse): Type {
            $typeClass = $type::class;

            if ($typeClass === 'PHPStan\Type\ObjectType') {
                $type = new ObjectType('\\' . $type->getClassName());
            }

            if ($typeClass === 'PHPStan\Type\Generic\GenericObjectType') {
                $type = new GenericObjectType('\\' . $type->getClassName(), $type->getTypes());
            }

            return $traverse($type);
        });

        return $type->toPhpDocNode();
    }

    /**
     * @param ObjectType $type
     */
    public function mapToPhpParserNode(Type $type, string $typeKind): ?Node
    {
        if ($type instanceof SelfObjectType) {
            return new Name('self');
        }

        if ($type instanceof ShortenedObjectType) {
            return new FullyQualified($type->getFullyQualifiedName());
        }

        if ($type instanceof AliasedObjectType) {
            return new Name($type->getClassName());
        }

        if ($type instanceof FullyQualifiedObjectType) {
            $className = $type->getClassName();

            if (str_starts_with($className, '\\')) {
                // skip leading \
                return new FullyQualified(Strings::substring($className, 1));
            }

            return new FullyQualified($className);
        }

        if ($type instanceof NonExistingObjectType) {
            return null;
        }

        return new FullyQualified($type->getClassName());
    }
}
