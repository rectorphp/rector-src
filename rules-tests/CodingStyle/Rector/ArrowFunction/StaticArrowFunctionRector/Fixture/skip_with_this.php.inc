<?php

namespace Rector\Tests\CodingStyle\Rector\ArrowFunction\StaticArrowFunctionRector\Fixture;

class SkipWithThis
{
    private $data = 'data';

    public function run()
    {
        fn(): string => $this->data;
    }
}
