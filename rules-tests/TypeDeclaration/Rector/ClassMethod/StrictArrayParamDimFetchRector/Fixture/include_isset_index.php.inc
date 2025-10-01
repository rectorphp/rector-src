<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\StrictArrayParamDimFetchRector\Fixture;

final class IncludeIssetIndex
{
    public function run($param): void
    {
        echo isset($param['index']) ? $param['index'] : 'test';
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\StrictArrayParamDimFetchRector\Fixture;

final class IncludeIssetIndex
{
    public function run(array $param): void
    {
        echo isset($param['index']) ? $param['index'] : 'test';
    }
}

?>
