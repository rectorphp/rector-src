<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\StrictArrayParamDimFetchRector\Fixture;

final class EmptyIndex
{
    public function run($param): void
    {
        if (! empty($param['index'])) {
            $index = $param['index'];
        } else {
            $index = 100;
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\StrictArrayParamDimFetchRector\Fixture;

final class EmptyIndex
{
    public function run(array $param): void
    {
        if (! empty($param['index'])) {
            $index = $param['index'];
        } else {
            $index = 100;
        }
    }
}

?>
