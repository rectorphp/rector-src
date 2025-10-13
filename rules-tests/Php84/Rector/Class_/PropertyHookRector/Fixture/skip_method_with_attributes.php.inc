<?php

namespace Rector\Tests\Php84\Rector\Class_\PropertyHookRector\Fixture;

use Symfony\Contracts\Service\Attribute\Required;

final class SkipMethodWithAttributes
{
    private string $name;

    #[Required]
    public function getName(): string
    {
        return $this->name;
    }

    #[Required]
    public function setName(string $name): void
    {
        $this->name = ucfirst($name);
    }
}
