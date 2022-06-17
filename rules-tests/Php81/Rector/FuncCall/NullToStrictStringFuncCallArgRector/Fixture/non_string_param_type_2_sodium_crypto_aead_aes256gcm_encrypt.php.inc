<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2SodiumCryptoAeadAes256gcmEncrypt
{
    public function run($message, $additional_data, $nonce, $key)
    {
        sodium_crypto_aead_aes256gcm_encrypt($message, $additional_data, $nonce, $key);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2SodiumCryptoAeadAes256gcmEncrypt
{
    public function run($message, $additional_data, $nonce, $key)
    {
        sodium_crypto_aead_aes256gcm_encrypt((string) $message, (string) $additional_data, (string) $nonce, (string) $key);
    }
}

?>
