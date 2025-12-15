<?php

namespace Rector\Tests\Arguments\Rector\FuncCall\FunctionArgumentDefaultValueReplacerRector\Fixture;

class SkipNamedArguments
{
    public function run()
    {
        version_compare(version1: '5.6', version2: PHP_VERSION, operator: 'lte');
        version_compare('5.6', PHP_VERSION, operator: 'lte');
    }
}
