<?php

namespace Rector\Tests\Issues\NamespacedUseAutoImport\Fixture;

use Rector\Tests\Issues\NamespacedUseAutoImport as Alias;

final class SkipNestedAliased
{
    public function run(): void
    {
        new Alias\Source\SomeClass();
    }
}
