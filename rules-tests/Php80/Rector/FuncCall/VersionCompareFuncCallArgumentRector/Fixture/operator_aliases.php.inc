<?php

namespace Rector\Tests\Php80\Rector\FuncCall\VersionCompareFuncCallArgumentRector\Fixture;

class OperatorAliases
{
    public function run()
    {
        version_compare(PHP_VERSION, '5.6', 'gte');
        version_compare(PHP_VERSION, '5.6', 'lte');
        version_compare(1, 2, '');
        version_compare(1, 2, '!');
        version_compare(PHP_VERSION, '5.6', 'g');
        version_compare(PHP_VERSION, '5.6', 'l');
        version_compare(PHP_VERSION, '5.6', 'n');
    }
}

?>
-----
<?php

namespace Rector\Tests\Php80\Rector\FuncCall\VersionCompareFuncCallArgumentRector\Fixture;

class OperatorAliases
{
    public function run()
    {
        version_compare(PHP_VERSION, '5.6', 'ge');
        version_compare(PHP_VERSION, '5.6', 'le');
        version_compare(1, 2, '!=');
        version_compare(1, 2, '!=');
        version_compare(PHP_VERSION, '5.6', 'gt');
        version_compare(PHP_VERSION, '5.6', 'lt');
        version_compare(PHP_VERSION, '5.6', 'ne');
    }
}

?>
