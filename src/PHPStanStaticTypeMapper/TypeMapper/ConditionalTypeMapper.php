<?php

declare(strict_types=1);

namespace Rector\PHPStanStaticTypeMapper\TypeMapper;

use Nette\Utils\Strings;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Type\ConditionalType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;
use PHPStan\Type\TypeTraverser;
use Rector\PHPStanStaticTypeMapper\Contract\TypeMapperInterface;

/**
 * @implements TypeMapperInterface<ConditionalType>
 */
final class ConditionalTypeMapper implements TypeMapperInterface
{
    /**
     * @return array<class-string<Type>>
     */
    public function getNodeClasses(): array
    {
        return [ConditionalType::class];
    }

    /**
     * @param ConditionalType $type
     */
    public function mapToPHPStanPhpDocTypeNode(Type $type): TypeNode
    {
        $type = TypeTraverser::map($type, static function (Type $type, callable $traverse): Type {
            if ($type instanceof ObjectType && ! $type->getClassReflection() instanceof ClassReflection) {
                $newClassName = (string) Strings::after($type->getClassName(), '\\', -1);
                return $traverse(new ObjectType($newClassName));
            }

            return $traverse($type);
        });

        return $type->toPhpDocNode();
    }
}
