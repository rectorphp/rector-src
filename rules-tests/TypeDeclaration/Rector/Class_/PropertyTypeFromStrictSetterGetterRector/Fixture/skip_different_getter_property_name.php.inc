<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\PropertyTypeFromStrictSetterGetterRector\Fixture;

final class SkipDifferenteGetterPropertyName
{
    private $name = 'John';

    private $differentName = 'Doe';

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getDifferentName(): string
    {
        return $this->differentName;
    }
}
