<?php

declare(strict_types=1);

namespace Rector\Tests\Php82\Rector\Class_\ReadOnlyClassRector\Source;

trait SomeTraitWithProperty
{
    public string $value = 'D';
}
