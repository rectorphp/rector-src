<?php

namespace Rector\Tests\CodingStyle\Rector\Stmt\NewlineAfterStatementRector\Fixture;

class SkipAlreadyHasNewline
{
    public function run()
    {
        if (rand(0,1)) {
        }

        if (rand(0,1)) {
        }
    }

    public function run2(array $data)
    {
        foreach ($data as $key => $value) {
        }

        return true;
    }
}

?>
