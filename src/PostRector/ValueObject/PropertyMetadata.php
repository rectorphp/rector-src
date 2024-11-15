<?php

declare(strict_types=1);

namespace Rector\PostRector\ValueObject;

use PhpParser\Modifiers;
use PHPStan\Type\Type;

final readonly class PropertyMetadata
{
    public function __construct(
        private string $name,
        private ?Type $type,
        private int $flags = Modifiers::PRIVATE,
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
