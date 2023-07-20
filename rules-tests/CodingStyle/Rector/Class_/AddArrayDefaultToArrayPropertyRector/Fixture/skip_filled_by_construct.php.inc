<?php

namespace Rector\Tests\CodingStyle\Rector\Class_\AddArrayDefaultToArrayPropertyRector\Fixture;

final class SkipFilledByConstruct
{
    /** @var string[] */
    private array $arr;

    public function __construct(array $arr)
    {
        $this->arr = $arr;
    }
}

