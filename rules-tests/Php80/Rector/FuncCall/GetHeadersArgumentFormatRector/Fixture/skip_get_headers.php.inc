<?php

namespace Rector\Tests\Php80\Rector\FuncCall\GetHeadersArgumentFormatRector\Fixture;

class SkipGetHeaders
{
    public function run($url, $format)
    {
        get_headers($url);
        get_headers($url, $format);
        get_headers($url, true);
        get_headers(...);
    }
}
