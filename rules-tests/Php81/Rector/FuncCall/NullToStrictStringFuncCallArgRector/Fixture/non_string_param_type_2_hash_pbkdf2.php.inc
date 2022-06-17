<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2HashPbkdf2
{
    public function run($algo, $password, $salt, $interations)
    {
        hash_pbkdf2($algo, $password, $salt, $interations);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2HashPbkdf2
{
    public function run($algo, $password, $salt, $interations)
    {
        hash_pbkdf2((string) $algo, (string) $password, (string) $salt, $interations);
    }
}

?>
