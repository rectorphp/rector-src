<?php

namespace Rector\Tests\Php71\Rector\Assign\AssignArrayToStringRector\Fixture;

final class SkipReAssignedAsString
{
    public function fun($cdate)
    {
        if ($cdate) {
            $where[] = 'n.sometable <= 1000';
        }

        if (empty($where)) {
            $where = '';
        } else {
            $where = 'WHERE ' . implode(' AND ', $where);
        }

        $sql = 'SELECT something, something FROM table1' . $where;
    }
}
