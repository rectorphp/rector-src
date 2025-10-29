<?php

namespace Rector\Tests\CodingStyle\Rector\FuncCall\FunctionFirstClassCallableRector\Fixture;

final class ArrayFilter
{
    public function filter(array $data)
    {
        return array_filter($data, 'intval');
    }
}

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\FuncCall\FunctionFirstClassCallableRector\Fixture;

final class ArrayFilter
{
    public function filter(array $data)
    {
        return array_filter($data, intval(...));
    }
}

?>
