<?php

namespace Rector\Tests\Php74\Rector\FuncCall\MbStrrposEncodingArgumentPositionRector\Fixture;

class SkipAlreadySetEncodingNamedArg
{
    public function run(string $text, string $encoding)
    {
        return mb_strrpos($text, 'abc', encoding: $encoding);
    }

    public function runWithOffset(string $text, int $offset, string $encoding)
    {
        return mb_strrpos($text, 'abc', $offset, encoding: $encoding);
    }

    public function runWithNamedOffset(string $text, int $offset, string $encoding)
    {
        return mb_strrpos($text, 'abc', encoding: $encoding, offset: $offset);
    }
}
