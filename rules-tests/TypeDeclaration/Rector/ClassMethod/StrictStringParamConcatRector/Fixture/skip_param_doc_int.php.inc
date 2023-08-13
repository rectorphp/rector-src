<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\StrictStringParamConcatRector\Fixture;

class SkipParamDocInt
{
    /**
     * @param int $total
     */
    public function resolve($total)
    {
        return 'we have ' . $total . 'item';
    }
}
