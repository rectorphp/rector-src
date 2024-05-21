<?php

namespace Rector\Tests\Php80\Rector\FunctionLike\MixedTypeRector\Fixture;

class SkipValidDoc
{
    /**
     * @param iterable<K, V>&\Countable $iterable
     *
     * @template K
     * @template V
     */
    public function run(iterable $iterable)
    {
    }
}
