<?php

declare(strict_types=1);

namespace Rector\PHPStanStaticTypeMapper\TypeMapper;

use PhpParser\Node;
use PhpParser\Node\Name;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\Type\ClassStringType;
use PHPStan\Type\Type;
use Rector\PHPStanStaticTypeMapper\Contract\TypeMapperInterface;

/**
 * @implements TypeMapperInterface<ClassStringType>
 */
final class ClassStringTypeMapper implements TypeMapperInterface
{
    /**
     * @return class-string<Type>
     */
    public function getNodeClass(): string
    {
        return ClassStringType::class;
    }

    /**
     * @param ClassStringType $type
     */
    public function mapToPHPStanPhpDocTypeNode(Type $type, string $typeKind): TypeNode
    {
        return new IdentifierTypeNode('class-string');
    }

    /**
     * @param ClassStringType $type
     */
    public function mapToPhpParserNode(Type $type, string $typeKind): ?Node
    {
        return new Name('string');
    }
}
