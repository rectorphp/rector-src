<?php

declare(strict_types=1);

namespace Rector\Config;

final readonly class RegisteredService
{
    public function __construct(
        private string $className,
        private ?string $alias,
        private ?string $tag
    ) {
    }

    public function getClassName(): string
    {
        return $this->className;
    }

    public function getAlias(): ?string
    {
        return $this->alias;
    }

    public function getTag(): ?string
    {
        return $this->tag;
    }
}
