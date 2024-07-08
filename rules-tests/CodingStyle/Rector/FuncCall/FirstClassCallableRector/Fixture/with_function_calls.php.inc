<?php

namespace Rector\Tests\CodingStyle\Rector\FuncCall\FirstClassCallableRector\Fixture;

final class WithFunctionCalls
{
    public function map(array $data)
    {
        return array_map(callback: 'strtolower', array: $data);
    }

    public function nestedMap(array $data)
    {
        return array_map('strtolower', array: array_map('trim', $data));
    }

    public function filter(array $data)
    {
        return array_filter($data, 'intval');
    }

    public function skipFilter(array $data)
    {
        return array_filter($data, boolval(...));
    }

    public function walkWithArgument(array $data, mixed $additionalArgument)
    {
        array_walk($data, 'serialize', $additionalArgument);

        return $data;
    }
}

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\FuncCall\FirstClassCallableRector\Fixture;

final class WithFunctionCalls
{
    public function map(array $data)
    {
        return array_map(callback: strtolower(...), array: $data);
    }

    public function nestedMap(array $data)
    {
        return array_map(strtolower(...), array: array_map(trim(...), $data));
    }

    public function filter(array $data)
    {
        return array_filter($data, intval(...));
    }

    public function skipFilter(array $data)
    {
        return array_filter($data, boolval(...));
    }

    public function walkWithArgument(array $data, mixed $additionalArgument)
    {
        array_walk($data, serialize(...), $additionalArgument);

        return $data;
    }
}

?>
