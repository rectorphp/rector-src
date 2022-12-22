<?php

namespace Rector\Tests\Php81\Rector\Property\ReadOnlyPropertyRector\Fixture;

final readonly class SkipOnReadonlyClass
{
    public function __construct(
        private string $name
    ) {
    }

    public function getName()
    {
        return $this->name;
    }
}