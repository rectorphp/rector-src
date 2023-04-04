<?php

namespace Rector\Core\Tests\Issues\NamespacedUse\Fixture;

use ReturnTypeWillChange;

class SkipUsedInAttribute implements \ArrayAccess
{
    #[ReturnTypeWillChange]
    public function offsetGet($offset)
    {
    }
}
