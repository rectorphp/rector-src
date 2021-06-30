<?php

declare(strict_types=1);

namespace Rector\PHPStanStaticTypeMapper\Contract;

use PhpParser\Node;
use PhpParser\Node\Name;
use PhpParser\Node\NullableType;
use PhpParser\Node\UnionType;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\Type\Type;
use Rector\PHPStanStaticTypeMapper\ValueObject\TypeKind;

/**
 * @template T of Type
 */
interface TypeMapperInterface
{
    /**
     * @return class-string<Type>
     */
    public function getNodeClass(): string;

    /**
     * @param T $type
     * @param TypeKind::*|null $kind
     *
     * @return Name|NullableType|UnionType|null
     */
    public function mapToPHPStanPhpDocTypeNode(Type $type, ?string $kind = null): TypeNode;
}
