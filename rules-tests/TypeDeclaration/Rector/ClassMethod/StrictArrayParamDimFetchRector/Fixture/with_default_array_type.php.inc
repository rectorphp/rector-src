<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\StrictArrayParamDimFetchRector\Fixture;

final class WithDefaultArrayType
{
    public function run($param = [])
    {
	    if (isset($param['bar'])) {
            echo $param['bar'];
        }
    }
}

?>
-----
<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\StrictArrayParamDimFetchRector\Fixture;

final class WithDefaultArrayType
{
    public function run(array $param = [])
    {
	    if (isset($param['bar'])) {
            echo $param['bar'];
        }
    }
}

?>