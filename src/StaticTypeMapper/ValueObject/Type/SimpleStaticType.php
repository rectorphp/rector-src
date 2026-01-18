<?php

declare(strict_types=1);

namespace Rector\StaticTypeMapper\ValueObject\Type;

use Override;
use PHPStan\Type\StaticType;

final class SimpleStaticType extends StaticType
{
    public function __construct(
        private readonly string $className
    ) {
    }

    #[Override]
    public function getClassName(): string
    {
        return $this->className;
    }
}
