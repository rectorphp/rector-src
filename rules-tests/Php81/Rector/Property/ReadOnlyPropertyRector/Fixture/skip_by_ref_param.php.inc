<?php

namespace Rector\Tests\Php81\Rector\Property\ReadOnlyPropertyRector\Fixture;

final class SkipByRefParam
{
    public function __construct(private  array &$idx)
    {
    }
}
