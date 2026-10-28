<?php

declare(strict_types=1);

namespace Rector\PHPStanStaticTypeMapper\Contract;

use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\Type\Type;

/**
 * @template TType of Type
 */
interface TypeMapperInterface
{
    /**
     * A mapper can cover several types at once, when they all map to the same output,
     * e.g. every accessory string type maps to "string"
     *
     * @return array<class-string<TType>>
     */
    public function getNodeClasses(): array;

    /**
     * @param TType $type
     */
    public function mapToPHPStanPhpDocTypeNode(Type $type): TypeNode;
}
