<?php

declare(strict_types=1);

namespace Rector\NodeTypeResolver\PHPStan;

use PhpParser\Node\ComplexType;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PHPStan\Type\NativeTypePosition;
use PHPStan\Type\ObjectWithoutClassType;
use PHPStan\Type\Type;
use PHPStan\Type\TypeWithClassName;

final class ObjectWithoutClassTypeWithParentTypes extends ObjectWithoutClassType
{
    /**
     * @param TypeWithClassName[] $parentTypes
     */
    public function __construct(
        private readonly array $parentTypes,
        ?Type $subtractedType = null
    ) {
        parent::__construct($subtractedType);
    }

    /**
     * @return TypeWithClassName[]
     */
    public function getParentTypes(): array
    {
        return $this->parentTypes;
    }

    public function toNativePhpTypeNode(NativeTypePosition $position): Identifier|Name|ComplexType|null
    {
        // anonymous class implementing a single other type
        if (count($this->parentTypes) === 1) {
            return $this->parentTypes[0]->toNativePhpTypeNode($position);
        }

        return parent::toNativePhpTypeNode($position);
    }
}
