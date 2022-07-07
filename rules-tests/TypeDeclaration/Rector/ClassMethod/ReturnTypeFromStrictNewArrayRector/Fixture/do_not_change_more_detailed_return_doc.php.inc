<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictNewArrayRector\Fixture;

final class DoNotChangeMoreDetailedReturnDoc
{
    /**
     * @param array<int|string, null|int|string> $ParameterArray
     *
     * @return array<string, string>
     */
    public function getFilterFromParameterArray2($ParameterArray) {

        $filter = [
            'where'  => '',
            'having' => '',
            'from'   => '',
        ];

        $where_filter_sql = '1=1';

        $where_filter_sql .= ' ... '; // sql stuff -> one line is working with reactor
        $where_filter_sql .= ' ... '; // sql stuff -> the next lines will break the phpdoc??

        $filter['where'] = $where_filter_sql;

        return $filter;
    }
}

?>
-----
<?php

declare(strict_types=1);

namespace Rector\Tests\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictNewArrayRector\Fixture;

final class DoNotChangeMoreDetailedReturnDoc
{
    /**
     * @param array<int|string, null|int|string> $ParameterArray
     *
     * @return array<string, string>
     */
    public function getFilterFromParameterArray2($ParameterArray): array {

        $filter = [
            'where'  => '',
            'having' => '',
            'from'   => '',
        ];

        $where_filter_sql = '1=1';

        $where_filter_sql .= ' ... '; // sql stuff -> one line is working with reactor
        $where_filter_sql .= ' ... '; // sql stuff -> the next lines will break the phpdoc??

        $filter['where'] = $where_filter_sql;

        return $filter;
    }
}

?>
