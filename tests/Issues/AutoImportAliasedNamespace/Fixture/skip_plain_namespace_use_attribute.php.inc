<?php

namespace Rector\Tests\Issues\AutoImportAliasedNamespace\Fixture;

use Rector\Tests\Issues\AutoImportAliasedNamespace\Source\Mapping;
use Rector\Tests\Issues\AutoImportAliasedNamespace\Source\Mapping as ORM;

final class SkipPlainNamespaceUseAttribute
{
    #[ORM\Column]
    public string $name;

    #[Mapping\Column]
    public string $title;
}
