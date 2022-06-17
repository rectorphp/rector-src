<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2SodiumCryptoSecretstreamXchacha20poly1305Push
{
    public function run($state, $message)
    {
        sodium_crypto_secretstream_xchacha20poly1305_push($state, $message);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2SodiumCryptoSecretstreamXchacha20poly1305Push
{
    public function run($state, $message)
    {
        sodium_crypto_secretstream_xchacha20poly1305_push($state, (string) $message);
    }
}

?>
