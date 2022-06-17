<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2StreamSocketSendto
{
    public function run($socket, $data)
    {
        stream_socket_sendto($socket, $data);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2StreamSocketSendto
{
    public function run($socket, $data)
    {
        stream_socket_sendto($socket, (string) $data);
    }
}

?>
