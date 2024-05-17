<?php

namespace Rector\Tests\TypeDeclaration\Rector\Empty_\EmptyOnNullableObjectToInstanceOfRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\Empty_\EmptyOnNullableObjectToInstanceOfRector\Source\AnotherObject;

final class SkipUnitializedProperty
{
    private AnotherObject $id;

    public function setId(AnotherObject $id): self
    {
        if (!empty($this->id) && !$id->equals($this->id)) {
            throw new \InvalidArgumentException('The ID is already set.');
        }

        $this->id = $id;

        return $this;
    }
}
