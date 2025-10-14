<?php

namespace Rector\Tests\Php84\Rector\Class_\PropertyHookRector\Fixture;

final class SkipReadonly
{
    private readonly string $name;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = ucfirst($name);
    }
}
