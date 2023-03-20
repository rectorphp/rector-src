<?php

namespace Rector\Tests\CodeQuality\Rector\Concat\JoinStringConcatRector\Fixture;

final class SkipWithMultiByte
{
    public function run(string $content)
    {
        return mb_strpos($content, "\x1f" . "\x8b" . "\x08") === 0;
    }
}