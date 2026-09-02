<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\ClassMethodArrayDocblockParamFromLocalCallsRector\Fixture;

class SkipHeterogeneousNestedArrayKey
{
    public function one()
    {
        return $this->sink([["x" => 1], "s"]);
    }

    public function two()
    {
        return $this->sink([[["d" => 1]]]);
    }

    protected function sink(array $data)
    {
        return $data;
    }
}
