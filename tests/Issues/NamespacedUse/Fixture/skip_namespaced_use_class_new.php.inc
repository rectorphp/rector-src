<?php

namespace Rector\Core\Tests\Issues\NamespacedUse\Fixture;

use Foo2;

final class SkipNamespacedUse2
{
    public function __construct()
    {
        new Foo2\Storage();
    }
}
