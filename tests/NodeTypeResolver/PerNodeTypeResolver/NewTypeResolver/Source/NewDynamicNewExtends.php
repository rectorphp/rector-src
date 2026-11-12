<?php

namespace Rector\Tests\NodeTypeResolver\PerNodeTypeResolver\NewTypeResolver\Source;

final class NewDynamicNewExtends
{
    public function run()
    {
        new class extends SomeRealType {};
    }
}
