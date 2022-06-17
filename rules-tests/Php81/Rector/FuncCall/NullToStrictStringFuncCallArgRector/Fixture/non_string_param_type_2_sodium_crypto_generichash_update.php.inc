<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2SodiumCryptoGenerichashUpdate
{
    public function run($state, $message)
    {
        sodium_crypto_generichash_update($state, $message);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2SodiumCryptoGenerichashUpdate
{
    public function run($state, $message)
    {
        sodium_crypto_generichash_update($state, (string) $message);
    }
}

?>
