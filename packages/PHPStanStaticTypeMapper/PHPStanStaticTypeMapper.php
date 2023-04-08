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

final class PHPStanStaticTypeMapper
{
    /**
     * @param TypeMapperInterface[] $typeMappers
     */
    public function __construct(
        private readonly array $typeMappers
    ) {
    }

    public function mapToPHPStanPhpDocTypeNode(Type $type, string $typeKind): TypeNode
    {
        foreach ($this->typeMappers as $typeMapper) {
            if (! is_a($type, $typeMapper->getNodeClass(), true)) {
                continue;
            }

            return $typeMapper->mapToPHPStanPhpDocTypeNode($type, $typeKind);
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
