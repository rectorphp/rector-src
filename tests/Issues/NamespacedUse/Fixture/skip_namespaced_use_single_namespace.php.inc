<?php

namespace Rector\Tests\Issues\NamespacedUse\Fixture;

use Foo2;

final class SkipNamespacedUseSingleNamespace
{
    public function __construct()
    {
        new Foo2\Storage();
    }
}
