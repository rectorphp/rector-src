<?php

namespace Rector\Tests\DeadCode\Rector\If_\RemoveAlwaysTrueIfConditionRector\Fixture;

trait SkipPropertyByVarDocInTrait
{
    /** @var Formatter|null */
    private $formatter;

    public function verify()
    {
        // if we don't have a formatter, make one
        if (! isset($this->formatter)) {
            // if no formatter, use the default
            $this->formatter = new Formatter();
        }

        $this->formatter->format();
    }
}
