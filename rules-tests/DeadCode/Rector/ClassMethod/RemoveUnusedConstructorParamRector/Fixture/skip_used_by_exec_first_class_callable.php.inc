<?php

namespace Rector\Tests\DeadCode\Rector\ClassMethod\RemoveUnusedConstructorParamRector\Fixture;

final class SkipUsedByExecFirstClassCallable
{
    private $hey;

    public function __construct($hey, $man)
    {
        $this->hey = $hey;

        $this->execute(...)($man);
    }

    private function execute($man)
    {
        echo $man;
    }
}
