<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2MbSendMail
{
    public function run($to, $subject, $message)
    {
        mb_send_mail($to, $subject, $message);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2MbSendMail
{
    public function run($to, $subject, $message)
    {
        mb_send_mail((string) $to, (string) $subject, (string) $message);
    }
}

?>
