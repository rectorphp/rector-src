<?php

namespace Rector\Tests\CodingStyle\Rector\ClassMethod\BinaryOpStandaloneAssignsToDirectRector\Fixture;

final class SkipNonVariableAssign
{
    private $number;

    public function run()
    {
        $first = 100;
        $this->number = 200;

        return $first <=> $this->number;
    }
}
