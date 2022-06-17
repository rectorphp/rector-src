<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2SocketSend
{
    public function run($socket, $data, $length, $flags)
    {
        socket_send($socket, $data, $length, $flags);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2SocketSend
{
    public function run($socket, $data, $length, $flags)
    {
        socket_send($socket, (string) $data, $length, $flags);
    }
}

?>
