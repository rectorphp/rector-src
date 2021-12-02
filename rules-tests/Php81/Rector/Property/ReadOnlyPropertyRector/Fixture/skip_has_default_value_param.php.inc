<?php

namespace Rector\Tests\Php81\Rector\Property\ReadOnlyPropertyRector\Fixture;

final class SkipHasDefaultValueParam
{
    public function __construct(
        private array $data = []
    ) {
    }

    public function getData()
    {
        return $this->data;
    }
}