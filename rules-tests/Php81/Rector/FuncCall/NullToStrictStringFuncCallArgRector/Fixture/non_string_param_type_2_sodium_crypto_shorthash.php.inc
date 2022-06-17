<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2SodiumCryptoShorthash
{
    public function run($message, $key)
    {
        sodium_crypto_shorthash($message, $key);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2SodiumCryptoShorthash
{
    public function run($message, $key)
    {
        sodium_crypto_shorthash((string) $message, (string) $key);
    }
}

?>
