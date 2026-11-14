<?php

namespace Rector\Tests\Issues\AutoImportAliasedNamespace;

final class SkipRelativeName
{
    public function run()
    {
        return new namespace\Source\Field\TextField();
    }
}
