<?php

declare(strict_types=1);

namespace Rector\PHPStanStaticTypeMapper\TypeMapper;

use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\Type\ClassStringType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use PHPStan\Type\TypeTraverser;
use Rector\PHPStanStaticTypeMapper\Contract\TypeMapperInterface;

/**
 * @implements TypeMapperInterface<ClassStringType>
 */
final readonly class ClassStringTypeMapper implements TypeMapperInterface
{
    /**
     * @return array<class-string<Type>>
     */
    public function getNodeClasses(): array
    {
        return [ClassStringType::class];
    }

    /**
     * @param ClassStringType $type
     */
    public function mapToPHPStanPhpDocTypeNode(Type $type): TypeNode
    {
        $type = TypeTraverser::map($type, static function (Type $type, callable $traverse): Type {
            if (! $type instanceof ObjectType) {
                return $traverse($type);
            }

            $typeClass = $type::class;

            if ($typeClass === 'PHPStan\Type\ObjectType') {
                return new ObjectType('\\' . $type->getClassName());
            }

            return $traverse($type);
        });

        return $type->toPhpDocNode();
    }
}
