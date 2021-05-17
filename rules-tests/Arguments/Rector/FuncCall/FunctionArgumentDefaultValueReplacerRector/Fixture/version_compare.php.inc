<?php

namespace Rector\Tests\Arguments\Rector\FuncCall\FunctionArgumentDefaultValueReplacerRector\Fixture;

class VersionCompare
{
    private const VERSION_COMPARE_OPERATOR = 'lte';

    public function run()
    {
        version_compare(1, 2);
        version_compare(1, 2, '');
        version_compare(1, 2, '!');
        version_compare(1, 2, 'g');
        version_compare(1, 2, 'l');
        version_compare(1, 2, 'n');

        version_compare(PHP_VERSION, '5.6', 'gte');
        version_compare(PHP_VERSION, '5.6', 'ge');
        version_compare(PHP_VERSION, '5.6', 'lte');
        version_compare(PHP_VERSION, '5.6', 'le');

        $operator = 'gte';
        version_compare(PHP_VERSION, '5.6', $operator);
        version_compare(PHP_VERSION, '5.6', self::VERSION_COMPARE_OPERATOR);
    }
}

?>
-----
<?php

namespace Rector\Tests\Arguments\Rector\FuncCall\FunctionArgumentDefaultValueReplacerRector\Fixture;

class VersionCompare
{
    private const VERSION_COMPARE_OPERATOR = 'lte';

    public function run()
    {
        version_compare(1, 2);
        version_compare(1, 2, '!=');
        version_compare(1, 2, '!=');
        version_compare(1, 2, 'gt');
        version_compare(1, 2, 'lt');
        version_compare(1, 2, 'ne');

        version_compare(PHP_VERSION, '5.6', 'ge');
        version_compare(PHP_VERSION, '5.6', 'ge');
        version_compare(PHP_VERSION, '5.6', 'le');
        version_compare(PHP_VERSION, '5.6', 'le');

        $operator = 'gte';
        version_compare(PHP_VERSION, '5.6', 'ge');
        version_compare(PHP_VERSION, '5.6', 'le');
    }
}

?>
