<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2SodiumCryptoSignVerifyDetached
{
    public function run($signature, $message, $public_key)
    {
        sodium_crypto_sign_verify_detached($signature, $message, $public_key);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2SodiumCryptoSignVerifyDetached
{
    public function run($signature, $message, $public_key)
    {
        sodium_crypto_sign_verify_detached((string) $signature, (string) $message, (string) $public_key);
    }
}

?>
