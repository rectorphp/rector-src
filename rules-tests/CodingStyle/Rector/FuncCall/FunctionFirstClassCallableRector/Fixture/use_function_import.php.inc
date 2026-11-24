<?php

namespace Rector\Tests\CodingStyle\Rector\FuncCall\FunctionFirstClassCallableRector\Fixture;

use function Rector\Tests\CodingStyle\Rector\FuncCall\FunctionFirstClassCallableRector\Source\strtoupper;

final class UseFunctionImport
{
    public function run(array $data)
    {
        return array_map('strtoupper', $data);
    }
}

?>
-----
<?php

namespace Rector\Tests\CodingStyle\Rector\FuncCall\FunctionFirstClassCallableRector\Fixture;

use function Rector\Tests\CodingStyle\Rector\FuncCall\FunctionFirstClassCallableRector\Source\strtoupper;

final class UseFunctionImport
{
    public function run(array $data)
    {
        return array_map(\strtoupper(...), $data);
    }
}

?>
