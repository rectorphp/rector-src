<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2SodiumCryptoSecretstreamXchacha20poly1305Pull
{
    public function run($state, $ciphertext)
    {
        sodium_crypto_secretstream_xchacha20poly1305_pull($state, $ciphertext);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2SodiumCryptoSecretstreamXchacha20poly1305Pull
{
    public function run($state, $ciphertext)
    {
        sodium_crypto_secretstream_xchacha20poly1305_pull($state, (string) $ciphertext);
    }
}

?>
