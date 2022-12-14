<?php

namespace Rector\Tests\DeadCode\Rector\StmtsAwareInterface\RemoveJustPropertyFetchRector\Fixture;

final class SkipAppendArrayData
{
    private $default = ['some data'];

    public function run()
    {
        $where = $this->default;
        $where[] = 'some append data';
    }
}
