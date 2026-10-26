<?php

declare(strict_types=1);

namespace App;

final class Customer
{
    public function __construct(
        private readonly string $name,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }
}
