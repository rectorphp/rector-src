<?php

namespace Rector\Tests\TypeDeclaration\Rector\Empty_\EmptyOnNullableObjectToInstanceOfRector\Fixture;

use Rector\Tests\TypeDeclaration\Rector\Empty_\EmptyOnNullableObjectToInstanceOfRector\Source\AnotherObject;

final class SkipUnitializedProperty
{
    private AnotherObject $data;

    public function setId(AnotherObject $data): self
    {
        if (!empty($this->data) && !$data->equals($this->id)) {
            throw new \InvalidArgumentException('The ID is already set.');
        }

        $this->data = $data;

        return $this;
    }
}
