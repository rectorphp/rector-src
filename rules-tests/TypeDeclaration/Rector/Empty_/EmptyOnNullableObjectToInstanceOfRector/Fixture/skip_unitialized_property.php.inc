<?php

namespace Rector\Tests\TypeDeclaration\Rector\Empty_\EmptyOnNullableObjectToInstanceOfRector\Fixture;

final class SkipUnitializedProperty
{
    private \stdClass $data;

    public function setId(\stdClass $data): self
    {
        if (!empty($this->data) && !$data->data($this->id)) {
            throw new \InvalidArgumentException('The ID is already set.');
        }

        $this->data = $data;

        return $this;
    }
}
