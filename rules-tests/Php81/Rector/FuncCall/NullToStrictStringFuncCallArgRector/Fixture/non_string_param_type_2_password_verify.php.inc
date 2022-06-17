<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2PasswordVerify
{
    public function run($password, $hash)
    {
        password_verify($password, $hash);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2PasswordVerify
{
    public function run($password, $hash)
    {
        password_verify((string) $password, (string) $hash);
    }
}

?>
