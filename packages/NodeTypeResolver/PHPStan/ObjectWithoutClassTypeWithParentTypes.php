<?php

declare(strict_types=1);

namespace Rector\NodeTypeResolver\PHPStan;

use PHPStan\Type\ObjectWithoutClassType;
use PHPStan\Type\TypeWithClassName;

final class ObjectWithoutClassTypeWithParentTypes extends ObjectWithoutClassType
{
    /**
     * @var TypeWithClassName[]
     */
    private array $parentTypes;

    /**
     * @param TypeWithClassName[] $parentTypes
     */
    public function __construct(array $parentTypes, ?\PHPStan\Type\Type $subtractedType = null)
    {
        parent::__construct($subtractedType);
        $this->parentTypes = $parentTypes;
    }

    /**
     * @return TypeWithClassName[]
     */
    public function getParentTypes(): array
    {
        return $this->parentTypes;
    }
}
