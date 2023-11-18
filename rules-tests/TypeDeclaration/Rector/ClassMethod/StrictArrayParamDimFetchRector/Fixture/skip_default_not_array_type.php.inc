<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\StrictArrayParamDimFetchRector\Fixture;

final class SkipDefaultNotArrayType
{
    public function run($param = 'foo')
    {
	 if (isset($param['bar'])) {
            echo $param['bar'];
        }

        echo $param;
    }
}
