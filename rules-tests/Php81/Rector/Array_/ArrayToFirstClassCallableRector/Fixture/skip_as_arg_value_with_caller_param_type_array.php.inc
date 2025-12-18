<?php

namespace Rector\Tests\Php81\Rector\Array_\ArrayToFirstClassCallableRector\Fixture;

final class SkipAsArgValueWithCallerParamTypeArray
{
    public function run(array $data)
    {
    }

    public function execute()
    {
        return $this->run([$this, 'some']);
    }

    public function some()
    {}
}