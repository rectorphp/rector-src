<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2SodiumCryptoSignOpen
{
    public function run($signed_message, $public_key)
    {
        sodium_crypto_sign_open($signed_message, $public_key);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2SodiumCryptoSignOpen
{
    public function run($signed_message, $public_key)
    {
        sodium_crypto_sign_open((string) $signed_message, (string) $public_key);
    }
}

?>
