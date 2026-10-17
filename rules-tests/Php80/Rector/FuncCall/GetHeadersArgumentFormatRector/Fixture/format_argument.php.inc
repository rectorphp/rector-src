<?php

namespace Rector\Tests\Php80\Rector\FuncCall\GetHeadersArgumentFormatRector\Fixture;

class FormatArgument
{
    public function run($url)
    {
        get_headers($url, 0);
        get_headers($url, 1);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\GetHeadersArgumentFormatRector\Fixture;

class FormatArgument
{
    public function run($url)
    {
        get_headers($url, false);
        get_headers($url, true);
    }
}

?>
