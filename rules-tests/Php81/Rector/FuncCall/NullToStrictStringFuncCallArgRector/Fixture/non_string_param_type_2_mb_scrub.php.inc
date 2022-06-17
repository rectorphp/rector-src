<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2MbScrub
{
    public function run($string)
    {
        mb_scrub($string);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2MbScrub
{
    public function run($string)
    {
        mb_scrub((string) $string);
    }
}

?>
