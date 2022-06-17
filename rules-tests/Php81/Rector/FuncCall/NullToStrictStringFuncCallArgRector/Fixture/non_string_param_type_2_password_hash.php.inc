<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2PasswordHash
{
    public function run($password, $algo, $options)
    {
        password_hash($password, $algo, $options);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2PasswordHash
{
    public function run($password, $algo, $options)
    {
        password_hash((string) $password, $algo, $options);
    }
}

?>
