<?php

declare(strict_types=1);

namespace Rector\Removing\ValueObject;

final class RemoveNamespace
{
    public function __construct(
        private readonly string $namespace
    ) {
    }

    public function getNamespace()
    {
        return $this->namespace;
    }
}
