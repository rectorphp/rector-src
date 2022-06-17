<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2IconvMimeEncode
{
    public function run($fieldName, $fieldValue)
    {
        iconv_mime_encode($fieldName, $fieldValue);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2IconvMimeEncode
{
    public function run($fieldName, $fieldValue)
    {
        iconv_mime_encode((string) $fieldName, (string) $fieldValue);
    }
}

?>
