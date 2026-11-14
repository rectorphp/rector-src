<?php

namespace Rector\Tests\Issues\AutoImportAliasedNamespace\Fixture;

use Rector\Tests\Issues\AutoImportAliasedNamespace\Source\Mapping;
use Rector\Tests\Issues\AutoImportAliasedNamespace\Source\Field;

/**
 * @Mapping\Entity()
 */
final class SkipPlainNamespaceUseDocblock
{
    /**
     * @Mapping\Column(name="name")
     * @var Field\TextField
     */
    public $textField;
}
