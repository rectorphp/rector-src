<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2SodiumCryptoAeadAes256gcmDecrypt
{
    public function run($ciphertext, $additional_data, $nonce, $key)
    {
        sodium_crypto_aead_aes256gcm_decrypt($ciphertext, $additional_data, $nonce, $key);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2SodiumCryptoAeadAes256gcmDecrypt
{
    public function run($ciphertext, $additional_data, $nonce, $key)
    {
        sodium_crypto_aead_aes256gcm_decrypt((string) $ciphertext, (string) $additional_data, (string) $nonce, (string) $key);
    }
}

?>
