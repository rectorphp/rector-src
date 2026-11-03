<?php

namespace Rector\Tests\Php74\Rector\FuncCall\MbStrrposEncodingArgumentPositionRector\Fixture;

class SkipMixedOffset
{
    public function run($string, $needle, $offset = 0)
    {
        return mb_strrpos($string, $needle, $offset);
    }

    public function runNamed($string, $needle, $encoding)
    {
        return mb_strrpos($string, $needle, encoding: $encoding);
    }
}
