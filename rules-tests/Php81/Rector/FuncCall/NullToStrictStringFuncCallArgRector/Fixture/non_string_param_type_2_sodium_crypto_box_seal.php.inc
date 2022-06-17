<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2SodiumCryptoBoxSeal
{
    public function run($message, $public_key)
    {
        sodium_crypto_box_seal($message, $public_key);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2SodiumCryptoBoxSeal
{
    public function run($message, $public_key)
    {
        sodium_crypto_box_seal((string) $message, (string) $public_key);
    }
}

?>
