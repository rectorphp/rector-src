<?php

namespace Rector\Tests\Issues\KeepPartiallyQualifiedNames\Fixture;

use Rector\Tests\Issues\KeepPartiallyQualifiedNames\Source;
use Rector\Tests\Issues\KeepPartiallyQualifiedNames\Source\Mapping;

/**
 * @Source\Mapping\Table(columns={@Source\Mapping\Column(name="x")})
 */
final class SkipPartiallyQualifiedAnnotation
{
    /**
     * @Mapping\Column(name="y")
     */
    public $name;
}
