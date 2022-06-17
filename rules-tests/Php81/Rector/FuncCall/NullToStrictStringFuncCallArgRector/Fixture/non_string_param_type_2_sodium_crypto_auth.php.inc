<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2SodiumCryptoAuth
{
    public function run($message, $key)
    {
        sodium_crypto_auth($message, $key);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2SodiumCryptoAuth
{
    public function run($message, $key)
    {
        sodium_crypto_auth((string) $message, (string) $key);
    }
}

?>
