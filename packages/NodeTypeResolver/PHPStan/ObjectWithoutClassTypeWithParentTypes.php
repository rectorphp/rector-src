<?php

declare(strict_types=1);

namespace Rector\NodeTypeResolver\PHPStan;

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
}
