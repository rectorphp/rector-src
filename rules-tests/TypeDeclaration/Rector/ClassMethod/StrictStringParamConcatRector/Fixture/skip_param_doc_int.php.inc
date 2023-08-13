<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\StrictStringParamConcatRector\Fixture;

class SkipParamDocInt
{
    /**
     * @param int
     */
    public function resolve($total)
    {
        return 'we have ' . $total . 'item';
    }
}
