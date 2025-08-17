<?php

namespace Rector\Tests\DeadCode\Rector\FunctionLike\NarrowTooWideReturnTypeRector\Fixture;

final class SkipDocblockCallOnArrowFunction
{
    public function run()
    {
        fn (): string|int => $this->execute();
    }

    /**
     * @return string
     */
    private function execute()
    {
        return 1;
    }
}

?>
