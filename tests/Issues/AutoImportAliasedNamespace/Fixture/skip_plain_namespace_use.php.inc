<?php

namespace Rector\Tests\Issues\AutoImportAliasedNamespace\Fixture;

use Rector\Tests\Issues\AutoImportAliasedNamespace\Source\Field;

final class SkipPlainNamespaceUse
{
    public function run(): Field\TextField
    {
        return new Field\TextField();
    }
}
