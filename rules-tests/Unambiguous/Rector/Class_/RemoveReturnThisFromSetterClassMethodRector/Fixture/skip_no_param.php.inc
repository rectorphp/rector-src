<?php

namespace Rector\Tests\Unambiguous\Rector\Class_\RemoveReturnThisFromSetterClassMethodRector\Fixture;

final class SkipNoParam
{
    private string $name;

    public function setName(): self
    {
        $this->name = 'name';

        return $this;
    }
}
