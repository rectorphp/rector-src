<?php

namespace Rector\Tests\Php81\Rector\Property\ReadOnlyPropertyRector\Fixture;

final class SkipCallByRef
{
    public function __construct(
        private array $someArray,
    ) {
        $this->doSomething($this->someArray);
    }

    public function doSomething(array &$someArray = null): void
    {
    }
}
