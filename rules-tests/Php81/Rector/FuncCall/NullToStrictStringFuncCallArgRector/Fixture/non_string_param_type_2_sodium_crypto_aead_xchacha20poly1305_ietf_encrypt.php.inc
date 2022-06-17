<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2SodiumCryptoAeadXchacha20poly1305IetfEncrypt
{
    public function run($message, $additional_data, $nonce, $key)
    {
        sodium_crypto_aead_xchacha20poly1305_ietf_encrypt($message, $additional_data, $nonce, $key);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2SodiumCryptoAeadXchacha20poly1305IetfEncrypt
{
    public function run($message, $additional_data, $nonce, $key)
    {
        sodium_crypto_aead_xchacha20poly1305_ietf_encrypt((string) $message, (string) $additional_data, (string) $nonce, (string) $key);
    }
}

?>
