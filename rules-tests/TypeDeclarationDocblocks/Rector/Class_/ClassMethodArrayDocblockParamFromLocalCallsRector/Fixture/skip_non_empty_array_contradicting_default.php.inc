<?php

namespace Rector\Tests\TypeDeclarationDocblocks\Rector\Class_\ClassMethodArrayDocblockParamFromLocalCallsRector\Fixture;

final class SkipNonEmptyArrayContradictingDefault
{
    public function callee(array $row = [])
    {
        return $row;
    }

    public function caller(array $row)
    {
        return $row['state'] && $this->callee($row);
    }

    public function caller2()
    {
        return $this->callee(['state' => 5, 'name' => 'x']);
    }
}
