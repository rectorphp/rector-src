<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2HashHmac
{
    public function run($algo, $data, $key)
    {
        hash_hmac($algo, $data, $key);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2HashHmac
{
    public function run($algo, $data, $key)
    {
        hash_hmac((string) $algo, (string) $data, (string) $key);
    }
}

?>
