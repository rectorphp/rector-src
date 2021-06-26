<?php

declare(strict_types=1);

namespace Rector\PHPStanStaticTypeMapper\TypeMapper;

use PhpParser\Node;
use PhpParser\Node\Name;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\Type\Type;
use Rector\PHPStanStaticTypeMapper\Contract\TypeMapperInterface;
use Rector\StaticTypeMapper\ValueObject\Type\ParentStaticType;

final class ParentStaticTypeMapper implements TypeMapperInterface
{
    /**
     * @return class-string<Type>
     */
    public function getNodeClass(): string
    {
        return ParentStaticType::class;
    }

    /**
     * @param ParentStaticType $type
     */
    public function mapToPHPStanPhpDocTypeNode(Type $type, ?string $kind = null): TypeNode
    {
        return new IdentifierTypeNode('parent');
    }

    /**
     * @param ParentStaticType $type
     */
    public function mapToPhpParserNode(Type $type, ?string $kind = null): ?Node
    {
        return new Name('parent');
    }
}
