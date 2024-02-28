<?php

namespace Rector\Tests\Naming\Rector\Class_\RenamePropertyToMatchTypeRector\Fixture;

class SkipNumericString
{
    /**
     * @var numeric-string|null
     */
    private ?string $value = null;
}
