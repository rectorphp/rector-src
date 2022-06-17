<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Mail
{
    public function run($to, $subject, $message)
    {
        mail($to, $subject, $message);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2Mail
{
    public function run($to, $subject, $message)
    {
        mail((string) $to, (string) $subject, (string) $message);
    }
}

?>
