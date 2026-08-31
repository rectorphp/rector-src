<?php

namespace Rector\Tests\TypeDeclaration\Rector\ArrowFunction\AddArrowFunctionReturnTypeRector\Fixture;

class SkipCoalesceMixed
{
    public function run()
    {
        fn (array $attributes) => $attributes['duration'] ?? null;
    }
}
