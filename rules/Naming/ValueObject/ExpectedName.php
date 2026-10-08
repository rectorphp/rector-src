<?php

declare(strict_types=1);

namespace Rector\Naming\ValueObject;

final readonly class ExpectedName
{
    public function __construct(
        private string $name
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }
}
