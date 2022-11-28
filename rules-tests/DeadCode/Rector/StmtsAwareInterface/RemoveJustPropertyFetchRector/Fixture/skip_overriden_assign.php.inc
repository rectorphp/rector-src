<?php

namespace Rector\Tests\DeadCode\Rector\StmtsAwareInterface\RemoveJustPropertyFetchRector\Fixture;

final class SkipOverridenAssign
{
    private $firstOption;

    private $secondOption;

    public function run()
    {
        $chosenOption = $this->firstOption;

        if (mt_rand(0, 10)) {
            $chosenOption = $this->secondOption;
        }

        return $chosenOption;
    }
}
