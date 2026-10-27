<?php

namespace Rector\Tests\Php71\Rector\BinaryOp\BinaryOpBetweenNumberAndStringRector\Fixture;

class SkipArrayDimFetchPhpdoc
{
    /**
     * @param array{adaid?: int} $where
     */
    public function run(array $where): void
    {
        if (array_key_exists('adaid', $where) && $where['adaid'] != '') {
        }
    }
}
