<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2SodiumCryptoBox
{
    public function run($message, $nonce, $key_pair)
    {
        sodium_crypto_box($message, $nonce, $key_pair);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2SodiumCryptoBox
{
    public function run($message, $nonce, $key_pair)
    {
        sodium_crypto_box((string) $message, (string) $nonce, (string) $key_pair);
    }
}

?>
