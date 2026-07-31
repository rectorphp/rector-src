<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveOverriddenPrivateMethodParameterRector\Fixture;

final class SkipSelfReferenceOverride
{
    public function run()
    {
        return $this->create(1000);
    }

    private function create($value)
    {
        $value = $value ?: 500;

        return $value;
    }
}
