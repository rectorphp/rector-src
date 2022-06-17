<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2SodiumCryptoAeadChacha20poly1305Encrypt
{
    public function run($message, $additional_data, $nonce, $key)
    {
        sodium_crypto_aead_chacha20poly1305_encrypt($message, $additional_data, $nonce, $key);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2SodiumCryptoAeadChacha20poly1305Encrypt
{
    public function run($message, $additional_data, $nonce, $key)
    {
        sodium_crypto_aead_chacha20poly1305_encrypt((string) $message, (string) $additional_data, (string) $nonce, (string) $key);
    }
}

?>
