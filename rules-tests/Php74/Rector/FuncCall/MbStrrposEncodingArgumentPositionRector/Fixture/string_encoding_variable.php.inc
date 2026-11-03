<?php

namespace Rector\Tests\Php74\Rector\FuncCall\MbStrrposEncodingArgumentPositionRector\Fixture;

class StringEncodingVariable
{
    public function run($text, string $encoding)
    {
        return mb_strrpos($text, 'abc', $encoding);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php74\Rector\FuncCall\MbStrrposEncodingArgumentPositionRector\Fixture;

class StringEncodingVariable
{
    public function run($text, string $encoding)
    {
        return mb_strrpos($text, 'abc', 0, $encoding);
    }
}

?>
