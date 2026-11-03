<?php

namespace Rector\Tests\Php74\Rector\FuncCall\MbStrrposEncodingArgumentPositionRector\Fixture;

class SkipSpreadOrDocblock
{
    public function runSpread(array $args)
    {
        return mb_strrpos(...$args);
    }

    public function runFirstClassCallable()
    {
        return mb_strrpos(...);
    }

    /**
     * @param string $encoding
     */
    public function runDocblock($text, $encoding)
    {
        return mb_strrpos($text, 'abc', $encoding);
    }
}
