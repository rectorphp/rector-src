<?php

namespace Rector\Tests\CodingStyle\Rector\FuncCall\FunctionFirstClassCallableRector\Fixture;

final class ArrayWalk
{
    public function walkWithArgument(array $data, mixed $additionalArgument)
    {
        array_walk($data, 'serialize', $additionalArgument);

        return $data;
    }
}

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\FuncCall\FunctionFirstClassCallableRector\Fixture;

final class ArrayWalk
{
    public function walkWithArgument(array $data, mixed $additionalArgument)
    {
        array_walk($data, serialize(...), $additionalArgument);

        return $data;
    }
}

?>
