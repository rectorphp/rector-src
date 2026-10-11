<?php

namespace Rector\Tests\CodeQuality\Rector\If_\ExplicitNullableObjectInstanceRector\Fixture;

use Rector\Tests\CodeQuality\Rector\If_\ExplicitNullableObjectInstanceRector\Source\AnotherObject;

final class SkipUninitializedProperty
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
