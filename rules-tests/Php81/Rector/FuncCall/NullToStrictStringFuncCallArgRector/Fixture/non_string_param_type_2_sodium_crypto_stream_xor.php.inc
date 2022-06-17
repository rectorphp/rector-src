<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2SodiumCryptoStreamXor
{
    public function run($message, $nonce, $key)
    {
        sodium_crypto_stream_xor($message, $nonce, $key);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2SodiumCryptoStreamXor
{
    public function run($message, $nonce, $key)
    {
        sodium_crypto_stream_xor((string) $message, (string) $nonce, (string) $key);
    }
}

?>
