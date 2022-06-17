<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2SodiumCryptoSign
{
    public function run($message, $secret_key)
    {
        sodium_crypto_sign($message, $secret_key);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2SodiumCryptoSign
{
    public function run($message, $secret_key)
    {
        sodium_crypto_sign((string) $message, (string) $secret_key);
    }
}

?>
