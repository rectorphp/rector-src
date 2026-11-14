<?php

namespace Rector\Tests\Issues\AutoImportAliasedNamespace\Fixture;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;

final class SkipDoctrineWithDirectImport
{
    #[ORM\Column]
    public string $name;

    public function run(): Column
    {
        return new ORM\Column();
    }
}
