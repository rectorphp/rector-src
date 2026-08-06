<?php

declare(strict_types=1);

namespace Rector\Tests\CodeQuality\Rector\Class_\InlineConstructorDefaultToPropertyRector\Source;

abstract class ValidatedBase
{
    protected function validate(): void
    {
    }
}
