<?php

namespace Rector\Tests\CodingStyle\Rector\FuncCall\FunctionFirstClassCallableRector\Fixture\NamespacedFunctionShadow;

function trim(string $value): string
{
    return 'shadowed';
}

final class NamespacedFunctionShadow
{
    public function run(array $data)
    {
        return array_map('trim', $data);
    }
}

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\FuncCall\FunctionFirstClassCallableRector\Fixture\NamespacedFunctionShadow;

function trim(string $value): string
{
    return 'shadowed';
}

final class NamespacedFunctionShadow
{
    public function run(array $data)
    {
        return array_map(\trim(...), $data);
    }
}

?>
