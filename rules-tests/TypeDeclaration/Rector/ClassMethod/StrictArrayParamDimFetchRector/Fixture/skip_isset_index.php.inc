<?php

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\StrictArrayParamDimFetchRector\Fixture;

final class SkipIssetIndex
{
    public function run($param): void
    {
     	echo isset($param['index']) ? $param['index'] : 'test';
    }
}
