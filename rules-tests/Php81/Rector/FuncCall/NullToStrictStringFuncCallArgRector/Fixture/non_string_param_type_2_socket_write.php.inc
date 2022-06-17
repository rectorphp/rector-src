<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2SocketWrite
{
    public function run($socket, $data)
    {
        socket_write($socket, $data);
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\NullToStrictStringFuncCallArgRector\Fixture;

final class NonStringParamType2SocketWrite
{
    public function run($socket, $data)
    {
        socket_write($socket, (string) $data);
    }
}

?>
