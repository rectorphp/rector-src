<?php

namespace Rector\Tests\Php84\Rector\Class_\PropertyHookRector\Fixture;

final class SkipMismatchingGetterSetterNames
{
    private string $name;

    public function getAnotherName(): string
    {
        return $this->name;
    }

    public function setAnotherName(string $name): void
    {
        $this->name = ucfirst($name);
    }
}
