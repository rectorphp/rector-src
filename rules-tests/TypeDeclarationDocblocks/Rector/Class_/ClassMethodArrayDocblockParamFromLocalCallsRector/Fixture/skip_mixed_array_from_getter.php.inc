<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\ClassMethodArrayDocblockParamFromLocalCallsRector\Fixture;

final class SkipMixedArrayFromGetter
{
    public function getter(): ?array
    {
    }

    public function fromGetter()
    {
        $this->run($this->getter());
    }

    private function run(array $p)
    {
    }
}