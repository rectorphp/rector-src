<?php

namespace Rector\Tests\Issues\AutoImportAliasedNamespace\Fixture;

use Rector\Tests\Issues\AutoImportAliasedNamespace\Source\Mapping as ORM;
use Rector\Tests\Issues\AutoImportAliasedNamespace\Source\Mapping\Column;

final class SkipAliasedWithDirectImport
{
    #[ORM\Column]
    public string $name;

    public function run(): Column
    {
        return new ORM\Column();
    }
}
