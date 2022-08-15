<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class CryptOneArg
{
    public function run($subject)
    {
        crypt($subject);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class CryptOneArg
{
    public function run($subject)
    {
        crypt((string) $subject);
    }
}

?>