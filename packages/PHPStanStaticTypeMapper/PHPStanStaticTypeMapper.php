<?php

declare(strict_types=1);

namespace Rector\PHPStanStaticTypeMapper;

use PhpParser\Node\ComplexType;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\Type\Accessory\HasMethodType;
use PHPStan\Type\ConditionalType;
use PHPStan\Type\Type;
use Rector\Core\Exception\NotImplementedYetException;
use Rector\PHPStanStaticTypeMapper\Contract\TypeMapperInterface;
use Rector\PHPStanStaticTypeMapper\Enum\TypeKind;
use Rector\PHPStanStaticTypeMapper\TypeMapper\ArrayTypeMapper;
use Rector\PHPStanStaticTypeMapper\TypeMapper\CallableTypeMapper;
use Rector\PHPStanStaticTypeMapper\TypeMapper\ClassStringTypeMapper;
use Rector\PHPStanStaticTypeMapper\TypeMapper\ClosureTypeMapper;
use Rector\PHPStanStaticTypeMapper\TypeMapper\ObjectTypeMapper;
use Rector\PHPStanStaticTypeMapper\TypeMapper\StringTypeMapper;
use Rector\PHPStanStaticTypeMapper\TypeMapper\UnionTypeMapper;

final class PHPStanStaticTypeMapper
{
    /**
     * @param TypeMapperInterface[] $typeMappers
     */
    public function __construct(
        private readonly iterable $typeMappers
    ) {
    }

    /**
     * @param TypeKind::* $typeKind
     */
    public function mapToPHPStanPhpDocTypeNode(Type $type, string $typeKind): TypeNode
    {
        foreach ($this->typeMappers as $typeMapper) {
            if (! is_a($type, $typeMapper->getNodeClass(), true)) {
                continue;
            }

            if ($typeMapper instanceof StringTypeMapper || $typeMapper instanceof ArrayTypeMapper || $typeMapper instanceof CallableTypeMapper || $typeMapper instanceof ClosureTypeMapper || $typeMapper instanceof ObjectTypeMapper || $typeMapper instanceof UnionTypeMapper || $typeMapper instanceof ClassStringTypeMapper) {
                return $typeMapper->mapToPHPStanPhpDocTypeNode($type, $typeKind);
            }

            return $type->toPhpDocNode(); // $typeMapper-> mapToPHPStanPhpDocTypeNode($type, $typeKind);
        }

        if ($type->isString()->yes()) {
            return new IdentifierTypeNode('string');
        }

        if ($type instanceof HasMethodType) {
            return new IdentifierTypeNode('object');
        }

        if ($type instanceof ConditionalType) {
            return new IdentifierTypeNode('mixed');
        }

        throw new NotImplementedYetException(__METHOD__ . ' for ' . $type::class);
    }

    /**
     * @param TypeKind::* $typeKind
     */
    public function mapToPhpParserNode(Type $type, string $typeKind): Name | ComplexType | Identifier | null
    {
        foreach ($this->typeMappers as $typeMapper) {
            if (! is_a($type, $typeMapper->getNodeClass(), true)) {
                continue;
            }

            return $typeMapper->mapToPhpParserNode($type, $typeKind);
        }

        throw new NotImplementedYetException(__METHOD__ . ' for ' . $type::class);
    }
}
