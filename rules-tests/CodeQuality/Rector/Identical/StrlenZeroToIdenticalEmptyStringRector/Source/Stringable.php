<?php

namespace Rector\Tests\CodeQuality\Rector\Identical\StrlenZeroToIdenticalEmptyStringRector\Source;

class Stringable
{
    private string $string = '';

    public function __toString() : string
    {
        return $this->string;
    }
}
