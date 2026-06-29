<?php

namespace Rector\Tests\DeadCode\Rector\For_\RemoveDeadIfForeachForRector\Fixture;

class SkipForeachOverCallSideEffect
{
    private $result;

    public function run()
    {
        if (! isset($this->result)) {
            foreach ($this->events() as $event) {
            }
        }

        return $this->result;
    }

    private function events(): iterable
    {
        $this->result = 1;
        yield 1;
    }
}
