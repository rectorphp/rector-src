<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2SodiumCryptoAeadChacha20poly1305IetfDecrypt
{
    public function run($ciphertext, $additional_data, $nonce, $key)
    {
        sodium_crypto_aead_chacha20poly1305_ietf_decrypt($ciphertext, $additional_data, $nonce, $key);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2SodiumCryptoAeadChacha20poly1305IetfDecrypt
{
    public function run($ciphertext, $additional_data, $nonce, $key)
    {
        sodium_crypto_aead_chacha20poly1305_ietf_decrypt((string) $ciphertext, (string) $additional_data, (string) $nonce, (string) $key);
    }
}

?>
