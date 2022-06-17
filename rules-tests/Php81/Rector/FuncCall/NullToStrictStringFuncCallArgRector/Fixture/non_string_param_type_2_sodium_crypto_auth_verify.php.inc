<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2SodiumCryptoAuthVerify
{
    public function run($mac, $message, $key)
    {
        sodium_crypto_auth_verify($mac, $message, $key);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2SodiumCryptoAuthVerify
{
    public function run($mac, $message, $key)
    {
        sodium_crypto_auth_verify((string) $mac, (string) $message, (string) $key);
    }
}

?>
