<?php

namespace Rector\Tests\Issues\AutoImportAliasedNamespace\Fixture;

use Rector\Tests\Issues\AutoImportAliasedNamespace\Source\Mapping as ORM;
use Rector\Tests\Issues\AutoImportAliasedNamespace\Source\Constraints as Assert;

/**
 * @ORM\Entity()
 */
final class SkipAliasedDocblock
{
    /**
     * @ORM\Column(name="name")
     * @var Assert\Choice
     */
    public $name;
}
