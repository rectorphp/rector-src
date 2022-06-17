<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2SodiumCryptoGenerichash
{
    public function run($message)
    {
        sodium_crypto_generichash($message);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2SodiumCryptoGenerichash
{
    public function run($message)
    {
        sodium_crypto_generichash((string) $message);
    }
}

?>
