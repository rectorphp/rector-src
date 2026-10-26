<?php

declare(strict_types=1);

namespace App;

final class Order
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
