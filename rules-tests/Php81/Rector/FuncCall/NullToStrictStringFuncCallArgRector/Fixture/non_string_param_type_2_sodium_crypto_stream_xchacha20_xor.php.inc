<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2SodiumCryptoStreamXchacha20Xor
{
    public function run($message, $nonce, $key)
    {
        sodium_crypto_stream_xchacha20_xor($message, $nonce, $key);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2SodiumCryptoStreamXchacha20Xor
{
    public function run($message, $nonce, $key)
    {
        sodium_crypto_stream_xchacha20_xor((string) $message, (string) $nonce, (string) $key);
    }
}

?>
