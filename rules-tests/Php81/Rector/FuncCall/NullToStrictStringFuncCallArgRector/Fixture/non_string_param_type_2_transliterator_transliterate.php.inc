<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2TransliteratorTransliterate
{
    public function run($transliterator, $string)
    {
        transliterator_transliterate($transliterator, $string);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2TransliteratorTransliterate
{
    public function run($transliterator, $string)
    {
        transliterator_transliterate($transliterator, (string) $string);
    }
}

?>
