<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\StrictArrayParamDimFetchRector\Fixture;

final class SkipEmptyIndex
{
    public function run($param): void
    {
     	echo ! empty($param['index']) ? $param['index'] : 'test';
    }
}
