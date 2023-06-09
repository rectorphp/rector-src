<?php

declare(strict_types=1);

namespace Rector\PostRector\ValueObject;

use PhpParser\Node\Stmt\Class_;
use PHPStan\Type\Type;

final class PropertyMetadata
{
    public function __construct(
        private readonly string $name,
        private readonly ?Type $type,
        private readonly int $flags = Class_::MODIFIER_PRIVATE,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): ?Type
    {
        return $this->type;
    }

    public function getFlags(): int
    {
        return $this->flags;
    }
}
