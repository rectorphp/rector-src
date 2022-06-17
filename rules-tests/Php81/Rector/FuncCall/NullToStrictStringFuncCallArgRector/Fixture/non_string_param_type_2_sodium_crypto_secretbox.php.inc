<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2SodiumCryptoSecretbox
{
    public function run($message, $nonce, $key)
    {
        sodium_crypto_secretbox($message, $nonce, $key);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2SodiumCryptoSecretbox
{
    public function run($message, $nonce, $key)
    {
        sodium_crypto_secretbox((string) $message, (string) $nonce, (string) $key);
    }
}

?>
