<?php

namespace Rector\Tests\Issues\NamespacedUseAutoImport\Fixture;

use Foo2;

final class NamespacedUseSingleNamespace
{
    public function __construct()
    {
        new Foo2\Storage();
    }
}

?>
