<?php

namespace Rector\Tests\DeadCode\Rector\Assign\RemoveUnusedVariableAssignRector\Fixture;

final class SkipShadowedLocalVariable
{
    public function run(array $params)
    {
        $toDate = null;
        if (isset($params['todate'])) {
            $toDate = \DateTime::createFromFormat('d.m.Y', $params['todate']);
        } else {
            $toDate = new \DateTime();
            $toDate->setTimestamp(strtotime('now - 3 month'));
        }

        return $toDate;
    }
}

