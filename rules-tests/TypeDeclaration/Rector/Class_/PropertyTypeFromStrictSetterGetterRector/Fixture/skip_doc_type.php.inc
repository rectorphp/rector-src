<?php

namespace Rector\Tests\TypeDeclaration\Rector\Class_\PropertyTypeFromStrictSetterGetterRector\Fixture;

final class SkipDocType
{
    private $name = 'John';

    /**
     * @param string $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }
}

