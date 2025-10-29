<?php

namespace Rector\Tests\CodingStyle\Rector\FuncCall\FunctionFirstClassCallableRector\Fixture;

final class NestedArrayCall
{
    public function nestedMap(array $data)
    {
        return array_map('strtolower', array: array_map('trim', $data));
    }
}

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\FuncCall\FunctionFirstClassCallableRector\Fixture;

final class NestedArrayCall
{
    public function nestedMap(array $data)
    {
        return array_map(strtolower(...), array: array_map(trim(...), $data));
    }
}

?>
