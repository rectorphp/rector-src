<?php

namespace Rector\Tests\CodingStyle\Rector\Closure\StaticClosureRector\Fixture;

class SkipWithThis
{
    private $data = 'data';

    public function run()
    {
        function () {
            if (rand(0, 1)) {
                return $this->data;
            }

            return strtoupper($this->data);
        };
    }
}
