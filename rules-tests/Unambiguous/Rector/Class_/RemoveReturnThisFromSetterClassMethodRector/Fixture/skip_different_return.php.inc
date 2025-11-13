<?php

namespace Rector\Tests\Unambiguous\Rector\Class_\RemoveReturnThisFromSetterClassMethodRector\Fixture;

final class SkipDifferentReturn
{
    private string $name;

    public function setName(string $name)
    {
        $this->name = $name;
        return $name;
    }
}
