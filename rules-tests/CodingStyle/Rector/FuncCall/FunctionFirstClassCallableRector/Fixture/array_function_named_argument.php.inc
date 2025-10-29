<?php

namespace Rector\Tests\CodingStyle\Rector\FuncCall\FunctionFirstClassCallableRector\Fixture;

final class ArrayFunctionNamedArgument
{
    public function map(array $data)
    {
        return array_map(callback: 'strtolower', array: $data);
    }
}

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\FuncCall\FunctionFirstClassCallableRector\Fixture;

final class ArrayFunctionNamedArgument
{
    public function map(array $data)
    {
        return array_map(callback: strtolower(...), array: $data);
    }
}

?>
