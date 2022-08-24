<?php

namespace Rector\Tests\DeadCode\Rector\Node\RemoveNonExistingVarAnnotationRector\Fixture;

final class KeepObjectShape
{
    public function get()
    {
        /** @var object{foo: string} $foo */
        $foo = json_decode('{"foo": "bar"}', false);
    }
}
