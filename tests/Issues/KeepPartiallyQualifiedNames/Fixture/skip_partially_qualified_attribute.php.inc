<?php

namespace Rector\Tests\Issues\KeepPartiallyQualifiedNames\Fixture;

use Rector\Tests\Issues\KeepPartiallyQualifiedNames\Source\Mapping;
use Rector\Tests\Issues\KeepPartiallyQualifiedNames\Source\Mapping as ORM;

final class SkipPartiallyQualifiedAttribute
{
    #[ORM\Column]
    public string $name;

    #[Mapping\Column]
    public string $title;
}
