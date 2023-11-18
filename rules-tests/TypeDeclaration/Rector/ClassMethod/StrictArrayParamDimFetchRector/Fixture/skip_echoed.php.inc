<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\StrictArrayParamDimFetchRector\Fixture;

final class SkipEchoed
{
    public function run($param)
    {
	 if (isset($param['bar'])) {
            echo $param['bar'];
        }

        echo $param;
    }
}

?>