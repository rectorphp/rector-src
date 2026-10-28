<?php

declare(strict_types=1);

namespace Rector\PHPStanStaticTypeMapper\TypeMapper;

use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\Type\IntegerType;
use PHPStan\Type\Type;
use Rector\PHPStanStaticTypeMapper\Contract\TypeMapperInterface;

/**
 * @implements TypeMapperInterface<IntegerType>
 */
final readonly class IntegerTypeMapper implements TypeMapperInterface
{
    /**
     * @return array<class-string<Type>>
     */
    public function getNodeClasses(): array
    {
        return [IntegerType::class];
    }

    /**
     * @param IntegerType $type
     */
    public function mapToPHPStanPhpDocTypeNode(Type $type): IdentifierTypeNode
    {
        // note: cannot be handled by PHPStan as uses explicit values
        return new IdentifierTypeNode('int');
    }
}
