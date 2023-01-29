<?php

declare(strict_types=1);

namespace Rector\Tests\Php81\Rector\Property\ReadOnlyPropertyRector\Source;

trait SomeTraitWithProperty
{
    public string $value = 'D';
}
